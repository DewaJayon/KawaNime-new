<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Http\Requests\Dashboard\StoreEpisodeRequest;
use App\Http\Requests\Dashboard\UpdateEpisodeRequest;
use App\Models\Anime;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Anime $anime)
    {
        $perPage = $request->input('per_page', 10);

        $episodes = Episode::query()
            ->where('anime_id', $anime->id)
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('episode_number', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Dashboard/Episode/Index', [
            'episodes' => $episodes,
            'anime'    => $anime->only('id', 'title', 'slug'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEpisodeRequest $request, Anime $anime)
    {
        $data = $request->validated();

        $data['slug'] = SlugService::createSlug(Anime::class, 'slug', $data['title']);

        if ($request->hasFile('video_url')) {
            // anime/naroto/episodes/1
            $filePath = "anime/$anime->slug/episodes/$data[episode_number]";
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Episode $episode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Episode $episode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEpisodeRequest $request, Episode $episode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Episode $episode)
    {
        //
    }
}
