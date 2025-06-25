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

    public $episode;

    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    public function handle(): void
    {
        $anime = $this->episode->anime;
        $slug = $anime->slug;
        $epSlug = $this->episode->slug;

        $basePath = "anime/{$slug}/episode/{$epSlug}";
        $originalPath = "{$basePath}/{$epSlug}.mp4";
        $thumbPath = "{$basePath}/thumbnail.jpg";
        $outputPath = "{$basePath}/converted";

        // Generate thumbnail
        FFMpeg::fromDisk('public')
            ->open($originalPath)
            ->getFrameFromSeconds(5)
            ->export()
            ->toDisk('public')
            ->save($thumbPath);

        // Cek apakah NVENC tersedia
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $cmd = $isWindows ? 'ffmpeg -hide_banner -encoders | findstr nvenc' : 'ffmpeg -hide_banner -encoders | grep nvenc';
        $nvidiaAvailable = str_contains(shell_exec($cmd), 'h264_nvenc');

        // Format untuk tiap resolusi
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

        // Simpan master playlist ke folder converted/master.m3u8
        $hls->save("{$outputPath}/master.m3u8");

        // Update ke DB
        $this->episode->update([
            'video_url' => "{$outputPath}/master.m3u8",
        ]);
    }
}
