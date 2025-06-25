<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Http\Requests\Dashboard\StoreEpisodeRequest;
use App\Http\Requests\Dashboard\UpdateEpisodeRequest;
use App\Jobs\ProcessEpisodeVideo;
use App\Models\Anime;
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
        return abort(404);
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

        ProcessEpisodeVideo::dispatch($episode->id, now());

        return back()->with('success', 'Episode berhasil ditambahkan! Video sedang diproses di latar belakang.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Episode $episode)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anime $anime, Episode $episode)
    {
        return Inertia::render('Dashboard/Episode/Edit', [
            'episode' => $episode,
            'anime'   => $anime->only('id', 'title', 'slug'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEpisodeRequest $request, Anime $anime, Episode $episode)
    {
        $data = $request->validated();

        if ($data['title'] !== $episode->title) {
            $data['slug'] = Str::slug("{$anime->slug}-episode-{$data['episode_number']}");
        }

        $data['anime_id'] = $anime->id;

        $episode->update($data);

        return redirect()->route('dashboard.episode.index', $anime->slug)->with('success', 'Episode berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anime $anime, Episode $episode)
    {
        $episodePath = $episode->video_url;

        if (Str::contains($episodePath, '/converted')) {
            $episodeDirectory = Str::before($episodePath, '/converted');
        } else {
            $episodeDirectory = Str::beforeLast($episodePath, '/');
        }

        $mp4File = "{$episodeDirectory}/{$episode->slug}.mp4";
        $thumbFile = "{$episodeDirectory}/thumbnail.jpg";

        if (Storage::disk('public')->exists($mp4File)) {
            Storage::disk('public')->delete($mp4File);
        }

        if (Storage::disk('public')->exists($thumbFile)) {
            Storage::disk('public')->delete($thumbFile);
        }

        $convertedPath = "{$episodeDirectory}/converted";

        if (Storage::disk('public')->exists($convertedPath)) {
            Storage::disk('public')->deleteDirectory($convertedPath);
        }

        Storage::disk('public')->deleteDirectory($episodeDirectory);

        $episode->delete();

        return back()->with('success', 'Episode berhasil dihapus!');
    }


    public function getConversionStatus(Anime $anime, Episode $episode)
    {
        return response()->json([
            'status' => $episode->conversion_status,
        ]);
    }
}
