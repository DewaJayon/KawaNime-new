<?php

use App\Http\Controllers\Dashboard\AnimeController;
use App\Http\Controllers\Dashboard\BannerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EpisodeController;
use App\Http\Controllers\Dashboard\GenreController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// Home Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/anime-list', [HomeController::class, 'animeList'])->name('anime-list');
Route::get('/watch', [HomeController::class, 'watch'])->name('watch');

// Dashboard Routes
Route::prefix('dashboard')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('banner', BannerController::class);
    Route::resource('genre', GenreController::class)->except(['create', 'show', 'edit']);

    Route::resource('user', UserController::class)->except(['create', 'show', 'edit']);
    Route::patch('user/reset-password/{user}', [UserController::class, 'resetPassword'])->name('dashboard.user.reset-password');

    Route::resource('/anime', AnimeController::class)->names('dashboard.anime')->except(['create', 'show', 'edit']);
    Route::get('/anime/create', function () {
        abort(404);
    });

    Route::resource('anime.episode', EpisodeController::class)->names('dashboard.episode')->except(['create', 'show', 'edit']);
    Route::get('/anime/{anime}/episode/create', function () {
        abort(404);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
