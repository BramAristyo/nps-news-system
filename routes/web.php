<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Front\NewsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public Routes
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');


// Internal Routes
require __DIR__.'/internal.php';

// Settings Routes (Profile & Password)
require __DIR__.'/settings.php';

// Dashboard Routes
require __DIR__.'/dashboard.php';

// Ping (for testing)
Route::get('/ping', function(){
    return Inertia::render('Ping');
})->name('ping');
