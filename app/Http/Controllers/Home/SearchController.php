<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    /**
     * Handle the incoming search request.
     *
     * This method retrieves a list of animes based on a search query
     * from the request. If the request expects a JSON response, it
     * returns the matched animes with their genres in JSON format,
     * limited to 10 results. Otherwise, it redirects back to the
     * previous page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public function index(Request $request)
    {
        $q = $request->query('q');

        $animes = Anime::with('genres')
            ->where('title', 'like', '%' . $q . '%')
            ->take(10)
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'animes' => $animes,
            ]);
        }

        return redirect()->back();
    }
}
