<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Anime extends Model
{
    use Sluggable;

    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'anime_genres', 'anime_id', 'genre_id')
                    ->using(AnimeGenre::class);;
    }

    public function animeGenres()
    {
        return $this->hasMany(AnimeGenre::class);
    }

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }
}
