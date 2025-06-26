<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use App\Models\Banner;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
