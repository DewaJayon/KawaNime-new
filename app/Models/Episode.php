<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use Sluggable;

    protected $fillable = [
        'anime_id',
        'title',
        'slug',
        'episode_number',
        'video_url',
        'thumbnail_url',
        'duration',
        'conversion_status',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }
}
