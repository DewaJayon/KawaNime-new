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
        $perPage = $request->query('per_page', 12);

        $query = Anime::with(['episodes' => function ($query) {
            $query->where('conversion_status', 'done')
                ->orderBy('episode_number', 'desc');
        }])->whereHas('episodes', function ($query) {
            $query->where('conversion_status', 'done');
        });

        if ($filter && $filter !== 'update-terbaru') {
            $query->where(function ($q) use ($filter) {
                $q->where("type", $filter)
                    ->orWhere("status", $filter);
            });
        }

        $animes = $query->orderBy(
            Episode::select('episode_number')
                ->whereColumn('anime_id', 'animes.id')
                ->where('conversion_status', 'done')
                ->orderBy('episode_number', 'desc')
                ->limit(1)
        )->paginate($perPage)->withQueryString();

        return Inertia::render('AnimeList/Index', [
            "animes" => $animes,
            "filter" => $filter,
        ]);
    }
}
