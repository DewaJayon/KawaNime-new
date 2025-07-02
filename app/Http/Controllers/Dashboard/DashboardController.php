<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $sumAnime = Anime::count();

        $animePerMonth = [];
        $month = [];

        for ($i = 1; $i <= date('m'); $i++) {
            $animePerMonth[] = DB::table('animes')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->count();

            $carbon = Carbon::create(null, $i, 1);
            $month[] = $carbon->locale('id')->translatedFormat('F');
        }

        return Inertia::render('Dashboard/Dashboard/Index', [
            'sumAnime'      => $sumAnime,
            'animePerMonth' => $animePerMonth,
            'month'         => $month
        ]);
    }
}
