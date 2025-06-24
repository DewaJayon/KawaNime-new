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
            // Tidak ada file baru, tapi cek apakah slug berubah
            if ($slugChanged && $anime->thumbnail) {
                // Pindahkan thumbnail lama ke folder slug baru
                $oldPath = str_replace('storage/', '', $anime->thumbnail);
                $oldFilename = basename($oldPath);
                $newPath = "anime/{$slug}/{$oldFilename}";

                // Buat folder baru kalau belum ada
                Storage::disk('public')->makeDirectory("anime/{$slug}");

                // Pindah file thumbnail lama
                Storage::disk('public')->move($oldPath, $newPath);

                $data['thumbnail'] = 'storage/' . $newPath;

                // Opsional: hapus direktori lama jika kosong
                $oldDir = dirname($oldPath);
                if (empty(Storage::disk('public')->files($oldDir))) {
                    Storage::disk('public')->deleteDirectory($oldDir);
                }
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
