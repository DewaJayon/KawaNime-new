<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use Sluggable;

    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    
    public function animes()
    {
        return $this->belongsToMany(Anime::class, 'anime_genre', 'genre_id', 'anime_id')
                    ->using(AnimeGenre::class);
    }

    public function animeGenres()
    {
        return $this->hasMany(AnimeGenre::class);
    }
}
