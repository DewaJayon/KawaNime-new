<?php

namespace App\FFMpeg;

use FFMpeg\Format\Video\DefaultVideo;
use FFMpeg\Exception\InvalidArgumentException;

class NvencFormat extends DefaultVideo
{
    protected $kiloBitrate;

    public function __construct()
    {
        $this->setVideoCodec('h264_nvenc');
        $this->setAudioCodec('aac');
    }

    public function getAvailableVideoCodecs(): array
    {
        return ['h264_nvenc'];
    }

    public function getAvailableAudioCodecs(): array
    {
        return ['aac'];
    }

    public function getExtraParams(): array
    {
        return ['-preset', 'p1', '-rc', 'vbr', '-cq', '28'];
    }

    public function getKiloBitrate(): ?int
    {
        return $this->kiloBitrate;
    }

    /**
     * Sets the kiloBitrate value.
     *
     * @param int $kiloBitrate
     *
     * @throws InvalidArgumentException
     */
    public function setKiloBitrate($kiloBitrate)
    {
        if ($kiloBitrate < 0) {
            throw new InvalidArgumentException('Wrong kiloBitrate value');
        }

        $this->kiloBitrate = (int) $kiloBitrate;

        return $this;
    }

    public function supportBFrames(): bool
    {
        return false;
    }
}
