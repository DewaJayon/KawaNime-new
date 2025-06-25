<?php

namespace App\Jobs;

use App\Models\Episode;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use App\FFMpeg\NvencFormat;
use FFMpeg\Format\Video\X264;

class ProcessEpisodeVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $episode;

    /**
     * Create a new job instance.
     */
    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $anime = $this->episode->anime;
        $slug = $anime->slug;
        $epSlug = $this->episode->slug;

        $basePath = "anime/{$slug}/episode/{$epSlug}";
        $originalPath = "{$basePath}/{$epSlug}.mp4";

        $thumbPath = "{$basePath}/thumbnail.jpg";

        FFMpeg::fromDisk('public')
            ->open($originalPath)
            ->getFrameFromSeconds(5)
            ->export()
            ->toDisk('public')
            ->save($thumbPath);

        $resolutions = [
            '360p'  => [640, 360],
            '480p'  => [854, 480],
            '720p'  => [1280, 720],
            '1080p' => [1920, 1080],
        ];

        $bitrates = [
            '360p'  => 600,
            '480p'  => 1000,
            '720p'  => 2500,
            '1080p' => 4500,
        ];

        $videoSources = [];

        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $cmd = $isWindows ? 'ffmpeg -hide_banner -encoders | findstr nvenc' : 'ffmpeg -hide_banner -encoders | grep nvenc';
        $nvidiaAvailable = str_contains(shell_exec($cmd), 'h264_nvenc');

        foreach ($resolutions as $label => [$w, $h]) {
            echo "Mulai konversi resolusi {$label} untuk {$this->episode->slug}\n";

            $convertedPath = "{$basePath}/converted/{$epSlug}-{$label}.mp4";

            if ($nvidiaAvailable) {
                $format = new NvencFormat();
                $format->setKiloBitrate($bitrates[$label]);
            } else {
                $format = new X264('aac', 'libx264');
                $format->setKiloBitrate($bitrates[$label]);
                $format->setAdditionalParameters(['-preset', 'ultrafast', '-crf', '28']);
            }

            FFMpeg::fromDisk('public')
                ->open($originalPath)
                ->export()
                ->toDisk('public')
                ->inFormat($format)
                ->resize($w, $h)
                ->save($convertedPath);

            $videoSources[$label] = $convertedPath;

            echo "Selesai {$label} untuk {$this->episode->slug}\n";
        }

        $this->episode->update([
            'video_sources' => $videoSources,
        ]);
    }
}
