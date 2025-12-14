<?php

use Inertia\Inertia;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\NewsController;

Route::middleware(['role-redirect'])->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('home');
});

Route::get('/news/internal', [NewsController::class, 'internal'])->name('news.internal')->middleware(['auth', 'verified', 'internal-user']);

Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware('role:admin,editor')->name('dashboard');
});

require __DIR__ . '/settings.php';
