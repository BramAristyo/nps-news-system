<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\NewsController;

Route::middleware(['auth', 'internal-user'])->group(function () {
    Route::get('/internal-news', [NewsController::class, 'internal'])->name('internal.news.index');
});
