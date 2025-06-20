<?php

use App\Http\Controllers\Dashboard\BannerController;
use App\Http\Controllers\Dashboard\DashboardController;
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
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
