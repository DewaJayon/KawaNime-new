<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Http\Requests\Dashboard\StoreEpisodeRequest;
use App\Http\Requests\Dashboard\UpdateEpisodeRequest;
use App\Jobs\ProcessEpisodeVideo;
use App\Models\Anime;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

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

        $data['slug'] = Str::slug("{$anime->slug}-episode-{$data['episode_number']}");
        $filename = $data['slug'];
        $basePath = "anime/{$anime->slug}/episode/{$filename}";

        if ($request->hasFile('video_url')) {
            $request->file('video_url')->storeAs($basePath, "{$filename}.mp4", 'public');
            $data['video_url'] = "{$basePath}/{$filename}.mp4";
        }

        $data['thumbnail_url'] = "{$basePath}/thumbnail.jpg";

        $data['anime_id'] = $anime->id;

        $episode = Episode::create($data);

        ProcessEpisodeVideo::dispatch($episode);

        return back()->with('success', 'Episode berhasil ditambahkan! Video sedang diproses di latar belakang.');
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
