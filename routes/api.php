<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommentController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('comments', CommentController::class);
    Route::post('/posts/{postId}/comments', [CommentController::class, 'store']);
    Route::get('/posts/{postId}/comments', [CommentController::class, 'index']);

    Route::post('logout', [LoginController::class, 'logout']);
});
Route::post('signup', [UserController::class, 'store'])->name('signup');
Route::post('login', [LoginController::class, 'login'])->name('login');

Route::get('/latest-posts', [PostController::class, 'getLatestPosts']);
Route::get('/random-users', [UserController::class, 'getRandomUsers']);


