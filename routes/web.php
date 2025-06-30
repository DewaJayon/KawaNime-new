<?php

use App\Http\Controllers\Dashboard\AnimeController;
use App\Http\Controllers\Dashboard\BannerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EpisodeController;
use App\Http\Controllers\Dashboard\GenreController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Home\AnimeListController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\SearchController;
use App\Http\Controllers\Home\WatchController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// Home Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/anime/{anime}', [HomeController::class, 'show'])->name('anime-detail');
Route::get('/watch/{episode}', [WatchController::class, 'show'])->name('watch');
Route::get('/search', [SearchController::class, 'index'])->name('anime.search');

Route::get('/anime-list', [AnimeListController::class, 'index'])->name('anime-list');

// Dashboard Routes
Route::prefix('dashboard')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/banner', BannerController::class);
    ROute::get('/banner/search/anime', [BannerController::class, 'searchAnime'])->name('dashboard.banner.search.anime');

    Route::resource('genre', GenreController::class)->except(['create', 'show', 'edit']);

    Route::resource('user', UserController::class)->except(['create', 'show', 'edit']);
    Route::patch('user/reset-password/{user}', [UserController::class, 'resetPassword'])->name('dashboard.user.reset-password');

    Route::resource('/anime', AnimeController::class)->names('dashboard.anime')->except(['create', 'show', 'edit']);
    Route::get('/anime/create', function () {
        abort(404);
    });

    Route::resource('anime.episode', EpisodeController::class)->names('dashboard.episode')->except(['show']);
    Route::get('/anime/{anime}/episode/{episode}/convert', [EpisodeController::class, 'getConversionStatus'])->name('dashboard.episode.convert');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
