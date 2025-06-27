<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use App\Models\Episode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WatchController extends Controller
{

    /**
     * Display the specified episode along with the next episode and recommendations.
     *
     * Loads the associated anime for the given episode and retrieves the next episode
     * in sequence. Generates a list of recommended animes based on shared genres,
     * excluding the current anime, and ensuring episodes are available.
     *
     * @param Episode $episode The episode to display.
     * @return \Inertia\Response The Inertia response for the watch page.
     */

    public function show(Episode $episode)
    {
        $episode->load('anime', 'anime.genres');

        $nextEpisode = $episode->anime
            ->episodes()
            ->where('episode_number', '>', $episode->episode_number)
            ->orderBy('episode_number', 'asc')
            ->first();

        $genreIds = $episode->anime->genres()->pluck('id')->toArray();

        $recomendations = Anime::where('id', '!=', $episode->anime_id)
            ->whereHas('genres', function ($query) use ($genreIds) {
                $query->whereIn('id', $genreIds);
            })
            ->whereHas('episodes', function ($query) {
                $query->where('conversion_status', 'done');
            })
            ->with('genres')
            ->limit(10)
            ->get();

        return Inertia::render('Watch/Index', [
            'episode'       => $episode,
            'nextEpisode'   => $nextEpisode,
            'recomendations' => $recomendations
        ]);
    }
}
