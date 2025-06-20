<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{

    public function index()
    {
        return Inertia::render('Home/Index');
    }

    public function animeList()
    {
        return Inertia::render('AnimeList/Index');
    }

    public function watch()
    {
        return Inertia::render('Watch/Index');
    }
}
