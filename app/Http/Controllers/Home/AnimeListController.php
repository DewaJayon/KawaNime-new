<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use App\Models\Episode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnimeListController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $filteredAnimes = Anime::query()
            ->with('episodes')
            ->where("type", $filter)
            ->orWhere("status", $filter)
            ->get();

        $defaultAnimes = Anime::whereHas('episodes', function ($query) {
            $query->where('conversion_status', 'done');
        })
            ->with(['episodes' => function ($query) {
                $query->where('conversion_status', 'done')
                    ->orderBy('episode_number', 'desc');
            }])
            ->orderBy(
                Episode::select('episode_number')
                    ->whereColumn('anime_id', 'animes.id')
                    ->where('conversion_status', 'done')
                    ->orderBy('episode_number', 'desc')
                    ->limit(1)
            )
            ->limit(12)
            ->get();

        if ($filter === "update-terbaru") {
            $filteredAnimes = $defaultAnimes;
        }

        if (!$filter) {
            $filteredAnimes = $defaultAnimes;
        }

        return Inertia::render('AnimeList/Index', [
            "filteredAnimes" => $filteredAnimes,
        ]);
    }
}
