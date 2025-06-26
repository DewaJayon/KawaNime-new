<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'anime_id',
        'headline',
        'subheadline',
        'image',
        'is_active',
    ];

    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }
}
