<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use App\Models\Banner;
use App\Models\Episode;
use Inertia\Inertia;

class HomeController extends Controller
{

    /**
     * Display the home page with active banners and recent episodes.
     *
     * This method fetches active banners along with their associated anime and episodes,
     * and the latest 10 episodes with completed conversion status. The data is then
     * passed to the 'Home/Index' view for rendering.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $banners = Banner::where('is_active', 1)->with('anime', 'anime.episodes')->get();
        $episodes = Episode::with('anime')->where('conversion_status', 'done')->latest()->take(10)->get();

        return Inertia::render('Home/Index', [
            'banners'   => $banners,
            'episodes'  => $episodes,
        ]);
    }


    /**
     * Display the anime detail page with episodes.
     *
     * This method fetches the anime by the given slug and its associated genres,
     * and the episodes with completed conversion status ordered by episode number.
     * The data is then passed to the 'AnimeDetail/Index' view for rendering.
     *
     * @param Anime $anime
     * @return \Inertia\Response
     */
    public function show(Anime $anime)
    {
        $anime->load('genres');

        $episodes = $anime->episodes()->where('conversion_status', 'done')->orderBy('episode_number', 'asc')->get();

        return Inertia::render('AnimeDetail/Index', [
            'anime'     => $anime,
            'episodes' => $episodes
        ]);
    }

    public function animeList()
    {
        return Inertia::render('AnimeList/Index');
    }
}
