<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $sumAnime = Anime::count();

        return Inertia::render('Dashboard/Dashboard/Index', [
            'sumAnime' => $sumAnime
        ]);
    }
}
