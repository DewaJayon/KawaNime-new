<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimeGenre extends Model
{
    protected $table = 'anime_genres'; 
    public $timestamps = false;

    protected $fillable = ['anime_id', 'genre_id'];

    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
