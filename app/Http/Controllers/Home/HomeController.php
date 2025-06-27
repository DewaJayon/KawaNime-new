<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use App\Models\Banner;
use App\Models\Episode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{

    /**
     * Home page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $banners = Banner::where('is_active', 1)->with('anime', 'anime.episodes')->get();
        $episodes = Episode::with('anime')->where('conversion_status', 'done')->latest()->take(10)->get();

        $query = $request->query('q');
        $animes = $this->searchAnime($query);

        return Inertia::render('Home/Index', [
            'banners'   => $banners,
            'episodes'  => $episodes,
            'animes'    => $animes,
            'query'     => $query
        ]);
    }

    /**
     * Search anime by title.
     *
     * @param  string|null  $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function searchAnime(?string $query)
    {
        $animeQuery = Anime::query();

        if (!empty($query)) {
            $animeQuery->with('genres')->where('title', 'like', "%{$query}%");
        } else {
            return [];
        }

        return $animeQuery->get();
    }

    public function show(Anime $anime)
    {
        $anime->load('episodes', 'genres');
        dd($anime->toArray());
    }

    public function animeList()
    {
        return Inertia::render('AnimeList/Index');
    }
}
