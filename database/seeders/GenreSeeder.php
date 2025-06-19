<?php

namespace Database\Seeders;

use App\Models\Genre;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $genres = json_decode(File::get(database_path('seeders/genres.json')), true);

        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre,
                'slug' => SlugService::createSlug(Genre::class, 'slug', $genre)
            ]);
        }
    }
}
