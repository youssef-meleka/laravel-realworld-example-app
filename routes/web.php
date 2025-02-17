<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleRevisionController;


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

Route::get('/', function () {
    return view('welcome');
});

// Article Revision Routes
Route::prefix('articles/{article}/revisions')->group(function () {
    Route::get('/', [ArticleRevisionController::class, 'index'])->name('articles.revisions.index'); // List all revisions
    Route::get('/{revision}', [ArticleRevisionController::class, 'show'])->name('articles.revisions.show'); // Display a specific revision
    Route::post('/{revision}/revert', [ArticleRevisionController::class, 'revert'])
        ->middleware('auth')
        ->name('articles.revisions.revert'); // Revert to a specific revision (Requires authentication)
});
