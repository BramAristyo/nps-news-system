<?php

use Inertia\Inertia;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\NewsController;
use App\Http\Controllers\Main\UserController;

Route::get('/', [NewsController::class, 'index'])->name('home');

Route::get('/news/internal', [NewsController::class, 'internal'])->name('news.internal')->middleware(['auth', 'verified', 'internal-user']);

Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

require __DIR__ . '/settings.php';
require __DIR__ . '/dashboard.php';