<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\NewsController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\UserController;

Route::middleware(['auth', 'role:admin,editor'])->prefix('dashboard')->name('dashboard.')->group(function () {
    
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // News Resource
    Route::resource('news', NewsController::class);

    // Categories Resource (Admin only)
    Route::resource('categories', CategoryController::class)->middleware('role:admin');

    // Users Resource (Admin only)
    Route::resource('users', UserController::class)->middleware('role:admin');
});

// Simple alias for 'dashboard' route (directly uses the controller)
Route::middleware(['auth', 'role:admin,editor'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
