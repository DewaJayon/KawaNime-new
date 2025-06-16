<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = ['id'];

    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }
}
