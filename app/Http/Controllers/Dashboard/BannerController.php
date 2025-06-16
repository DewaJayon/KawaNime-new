<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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
        
        $banners = Banner::query()
            ->when($request->search, function ($query, $search) {
                $query->where('headline', 'like', "%{$search}%");
            })
            ->orderBy(
                $request->sort ? explode('_', $request->sort)[0] : 'id',
                $request->sort && explode('_', $request->sort)[1] === 'desc' ? 'desc' : 'asc'
            )
            ->paginate($request->per_page ?? 10);
        

        return Inertia::render('Dashboard/Banner/Index', [
            'banners'   => $banners
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
