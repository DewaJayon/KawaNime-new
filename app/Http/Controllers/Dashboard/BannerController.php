<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreBannerRequest;
use App\Http\Requests\Dashboard\UpdateBannerRequest;
use App\Models\Anime;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $perPage    = $request->input('per_page', 10);

        $banners     =  Banner::query()
            ->with('anime')
            ->when($request->search, function ($query, $search) {
                return $query->where('headline', 'like', '%' . $search . '%')
                    ->orWhere('subheadline', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Dashboard/Banner/Index', [
            'banners'   => $banners
        ]);
    }

    /**
     * Search anime by title.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchAnime(Request $request)
    {
        $q = $request->input('q');

        if (empty($q)) {
            return response()->json([]);
        }

        $search = Anime::where('title', 'like', '%' . $q . '%')->limit(5)->get();

        return response()->json($search);
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
    public function store(StoreBannerRequest $request)
    {
        $data = $request->validated();

        $anime = Anime::find($data['anime_id']);

        if ($request->hasFile('image')) {
            $animePath = Str::chopStart($anime->thumbnail, 'storage/');
            $animePath = Str::beforeLast($animePath, '/');

            $filePath = "{$animePath}/banners";


            $fileName = time() . '-' . Str::random(6) . '.' . $request->file('image')->getClientOriginalExtension();
            $fullPath = $filePath . '/' . $fileName;

            $image = Image::read($request->file('image'))->resize(1920, 1080);

            Storage::disk('public')->put($fullPath, $image->encodeByExtension($request->file('image')->getClientOriginalExtension(), quality: 80));

            $data['image'] = 'storage/' . $fullPath;
        }

        Banner::create($data);

        return back()->with('success', 'Banner berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($banner->image) {
                $oldPath = Str::chopStart($banner->image, 'storage/');
                Storage::disk('public')->delete($oldPath);
                $folderPath = Str::beforeLast($oldPath, '/');
            } else {
                $folderPath = 'banners';
            }

            $fileName = time() . '-' . Str::random(6) . '.' . $request->file('image')->getClientOriginalExtension();
            $fullPath = $folderPath . '/' . $fileName;

            $image = Image::read($request->file('image'))->resize(1920, 1080);
            Storage::disk('public')->put($fullPath, $image->encodeByExtension($request->file('image')->extension(), quality: 80));

            $data['image'] = 'storage/' . $fullPath;
        } else {
            $data['image'] = $banner->image;
        }

        $banner->update($data);

        return back()->with('success', 'Banner berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        try {
            $filePath = Str::chopStart($banner->image, 'storage/');

            if ($banner->image) {
                Storage::disk('public')->delete($filePath);
            }

            $banner->delete();

            return back()->with('success', 'Banner berhasil dihapus!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Banner gagal dihapus!');
        }
    }
}
