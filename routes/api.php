<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\ArticleController as V1ArticleController;
use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V2\ArticleController as V2ArticleController;

// Group routes that require authentication using Sanctum middleware
Route::middleware('auth:sanctum')->group(function () {
    // Route to get the authenticated user's details
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Route to handle user logout
    Route::post('/logout', [AuthController::class, 'Logout']);
});

// Public routes for registration and login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Group API v1 routes
Route::prefix('V1')->group(function () {
    // Route to list articles
    Route::get('/ArticleHub/ListOfArticles', [V1ArticleController::class, 'index']);
    // Route to store a new article
    Route::post('/ArticleHub/StoreArticle', [V1ArticleController::class, 'store']);
    // Route to show a specific article by ID
    Route::get('/ArticleHub/ShowArticle/{id}', [V1ArticleController::class, 'show']);
    // Route to update a specific article by ID
    Route::put('/ArticleHub/UpdateArticle/{id}', [V1ArticleController::class, 'update']);
    // Route to delete a specific article by ID
    Route::delete('/ArticleHub/DeleteArticle/{id}', [V1ArticleController::class, 'destroy']);
    // Route to search for articles
    Route::get('/ArticleHub/Search', [V1ArticleController::class, 'index']);
});

// Group API v2 routes
Route::prefix('V2')->group(function () {
    // Route to list articles
    Route::get('/ArticleHub/ListOfArticles', [V2ArticleController::class, 'index']);
    // Resource routes for articles
    Route::resource('ArticleHub/ListOfArticles/Resources', V2ArticleController::class);
});
