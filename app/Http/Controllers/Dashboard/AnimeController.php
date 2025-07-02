<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use App\Http\Requests\Dashboard\StoreAnimeRequest;
use App\Http\Requests\Dashboard\UpdateAnimeRequest;
use App\Models\Genre;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;

class AnimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage    = $request->input('per_page', 10);

        $animes     = Anime::query()
            ->with('genres')
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();


        $genres = Genre::select('id', 'name as label')->get()->map(function ($genre) {
            return [
                'label' => $genre->label,
                'value' => $genre->id,
            ];
        });

        return Inertia::render('Dashboard/Anime/Index', [
            'animes' => $animes,
            'genres' => $genres
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnimeRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = SlugService::createSlug(Anime::class, 'slug', $data['title']);

        if ($request->hasFile('thumbnail')) {
            $filePath = 'anime/' . $data['slug'];
            $fileName = time() . '-' . Str::random(6) . '.' . $request->file('thumbnail')->getClientOriginalExtension();

            $fullPath = $filePath . '/' . $fileName;

            $image = Image::read($request->file('thumbnail'))
                ->resize(300, 450);

            Storage::disk('public')->put($fullPath, $image->encodeByExtension($request->file('thumbnail')->getClientOriginalExtension(), quality: 80));

            $data['thumbnail'] = 'storage/' . $fullPath;
        }

        $anime = Anime::create($data);

        $anime->genres()->sync($request->genre_ids);

        return back()->with('success', 'Anime berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnimeRequest $request, Anime $anime)
    {
        $data = $request->validated();

        $slugChanged = $data['title'] !== $anime->title;

        if ($slugChanged) {
            $data['slug'] = SlugService::createSlug(Anime::class, 'slug', $data['title']);
        }

        $slug = $data['slug'] ?? $anime->slug;

        if ($request->hasFile('thumbnail')) {

            if ($anime->thumbnail) {
                Storage::disk('public')->delete(str_replace('storage/', '', $anime->thumbnail));
            }

            $filePath = "anime/{$slug}";
            $fileName = time() . '-' . Str::random(6) . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $fullPath = $filePath . '/' . $fileName;

            $image = Image::read($request->file('thumbnail'))->resize(300, 450);

            Storage::disk('public')->put($fullPath, $image->encodeByExtension(
                $request->file('thumbnail')->getClientOriginalExtension(),
                quality: 80
            ));

            $data['thumbnail'] = 'storage/' . $fullPath;
        } else {

            if ($slugChanged && $anime->thumbnail) {
                $oldSlug = $anime->slug;
                $newSlug = $slug;

                $oldPath = "anime/{$oldSlug}";
                $newPath = "anime/{$newSlug}";

                Storage::disk('public')->makeDirectory($newPath);

                $files = Storage::disk('public')->allFiles($oldPath);

                foreach ($files as $file) {
                    $newFilePath = str_replace($oldPath, $newPath, $file);
                    Storage::disk('public')->copy($file, $newFilePath);
                }

                Storage::disk('public')->deleteDirectory($oldPath);

                if ($anime->banners()->count() > 0) {
                    $banners = $anime->banners()->get();
                    foreach ($banners as $banner) {
                        $banner->update([
                            'image' => "storage/{$newPath}/banners/" . basename($banner->image),
                        ]);
                    }
                }

                if ($anime->episodes()->count() > 0) {
                    $episodes = $anime->episodes()->get();
                    foreach ($episodes as $episode) {
                        $episode->update([
                            'video_url' => "anime/{$newSlug}/episode/{$episode->slug}/converted/" . basename($episode->video_url),
                            'thumbnail_url' => "anime/{$newSlug}/episode/{$episode->slug}/" . basename($episode->thumbnail_url),
                        ]);
                    }
                }

                $thumbnailFilename = basename($anime->thumbnail);
                $data['thumbnail'] = 'storage/' . $newPath . '/' . $thumbnailFilename;
            } else {
                $data['thumbnail'] = $anime->thumbnail;
            }
        }

        $anime->update($data);
        $anime->genres()->sync($request->genre_ids);

        return back()->with('success', 'Anime berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anime $anime)
    {
        try {

            if ($anime->episodes()->count() > 0) {
                $anime->episodes()->delete();
            }

            if ($anime->thumbnail || $anime->banners()->count() > 0) {
                Storage::disk('public')->deleteDirectory('anime/' . $anime->slug);
            }

            $anime->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return back()->with('success', 'Anime berhasil dihapus!');
    }
}
