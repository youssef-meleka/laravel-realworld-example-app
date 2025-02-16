<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ArticleController,
    CommentController,
    ProfileController,
    UserController,
    TagController,
    ArticleRevisionController
};

// Public Routes
Route::get('profiles/{user}', [ProfileController::class, 'show']);
Route::get('tags', [TagController::class, 'index']);
Route::get('articles/{article}/comments', [CommentController::class, 'index']);

// User Authentication Routes
Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::post('/', 'store');
    Route::post('login', 'login');
});

// Article Routes (Public)
Route::prefix('articles')->controller(ArticleController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('feed', 'feed');
    Route::get('{article}', 'show');
});

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    // User Routes
    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/', 'show');
        Route::put('/', 'update');
    });

    // Profile Follow/Unfollow
    Route::prefix('profiles')->controller(ProfileController::class)->group(function () {
        Route::post('{user}/follow', 'follow');
        Route::delete('{user}/follow', 'unfollow');
    });

    // Article Actions (Authenticated)
    Route::prefix('articles')->controller(ArticleController::class)->group(function () {
        Route::post('/', 'store');
        Route::put('{article}', 'update');
        Route::delete('{article}', 'destroy');
        Route::post('{article}/favorite', 'favorite');
        Route::delete('{article}/favorite', 'unfavorite');
    });

    // Article Comments
    Route::prefix('articles/{article}/comments')->controller(CommentController::class)->group(function () {
        Route::post('/', 'store');
        Route::delete('{comment}', 'destroy');
    });
});

// Article Revisions (Authenticated API Access)
Route::middleware('auth:sanctum')->prefix('articles/{article}/revisions')->controller(ArticleRevisionController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('{revision}', 'show');
    Route::post('{revision}/revert', 'revert');
});
