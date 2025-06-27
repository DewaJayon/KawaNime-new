<?php

namespace App\Jobs;

use App\Models\Episode;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;
use App\FFMpeg\NvencFormat;

class ProcessEpisodeVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $episodeId, public $dispatchedAt) {}

    public function handle(): void
    {
        $episode = Episode::with('anime')->find($this->episodeId);

        if (!$episode) {
            return;
        }

        if ($episode->updated_at > $this->dispatchedAt) {
            return;
        }

        $anime = $episode->anime;
        $slug = $anime->slug;
        $epSlug = $episode->slug;

        $basePath = "anime/{$slug}/episode/{$epSlug}";
        $originalPath = "{$basePath}/{$epSlug}.mp4";
        $thumbPath = "{$basePath}/thumbnail.jpg";
        $outputPath = "{$basePath}/converted";

        if (!Storage::disk('public')->exists($originalPath)) {
            sleep(3);

            if (!Storage::disk('public')->exists($originalPath)) {
                $episode->update(['conversion_status' => 'failed']);
                return;
            }
        }

        $episode->update(['conversion_status' => 'processing']);

        try {
            FFMpeg::fromDisk('public')
                ->open($originalPath)
                ->getFrameFromSeconds(60)
                ->export()
                ->toDisk('public')
                ->save($thumbPath);

            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
            $cmd = $isWindows ? 'ffmpeg -hide_banner -encoders | findstr nvenc' : 'ffmpeg -hide_banner -encoders | grep nvenc';
            $nvidiaAvailable = str_contains(shell_exec($cmd), 'h264_nvenc');

            $formats = [
                '360p'  => ['scale' => '640:360',  'bitrate' => 600],
                '480p'  => ['scale' => '854:480',  'bitrate' => 1000],
                '720p'  => ['scale' => '1280:720', 'bitrate' => 2500],
                '1080p' => ['scale' => '1920:1080', 'bitrate' => 4500],
            ];

            $hls = FFMpeg::fromDisk('public')
                ->open($originalPath)
                ->exportForHLS()
                ->toDisk('public');

            foreach ($formats as $label => $config) {
                $format = $nvidiaAvailable
                    ? (new NvencFormat())
                    : (new X264('aac', 'libx264'));

                $format->setKiloBitrate($config['bitrate']);

                $hls->addFormat($format, function ($media) use ($config) {
                    $media->addFilter('scale=' . $config['scale']);
                });
            }

            $hls->save("{$outputPath}/master.m3u8");

            $episode->update([
                'conversion_status' => 'done',
                'video_url' => "{$outputPath}/master.m3u8",
            ]);
        } catch (\Exception $e) {

            if (!Episode::find($this->episodeId)) {
                Storage::disk('public')->deleteDirectory($basePath);
            } else {
                $episode->update(['conversion_status' => 'failed']);
            }
        }
    }
}
