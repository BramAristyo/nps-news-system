<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\NewsController;
use App\Http\Controllers\Dashboard\CategoryController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
      return Inertia::render('Dashboard');
    })->middleware('role:admin,editor')->name('dashboard');
});

Route::middleware(['auth', 'verified', 'role:admin,editor'])->prefix('manage/users')->name('manage.users.')->controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'destroy')->name('destroy');
});

Route::get('manage/news', [NewsController::class, 'index'])->name('news.index')->middleware('role:admin,editor');

Route::middleware(['auth', 'verified', 'role:admin,editor'])->prefix('manage/categories')->name('manage.categories.')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'destroy')->name('destroy');
});

