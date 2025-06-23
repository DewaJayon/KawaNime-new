<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Anime extends Model
{
    use Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'type',
        'status',
        'studio',
        'release_date',
        'director',
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

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }
}
