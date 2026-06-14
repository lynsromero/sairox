<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{id}', [PostController::class, 'show'])->whereNumber('id');
    Route::get('posts/slug/{slug}', [PostController::class, 'showBySlug']);

    Route::get('pages', [PageController::class, 'index']);
    Route::get('pages/{id}', [PageController::class, 'show'])->whereNumber('id');
    Route::get('pages/slug/{slug}', [PageController::class, 'showBySlug']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show'])->whereNumber('id');
    Route::get('categories/slug/{slug}', [CategoryController::class, 'showBySlug']);

    Route::get('tags', [TagController::class, 'index']);
    Route::get('tags/{id}', [TagController::class, 'show'])->whereNumber('id');
    Route::get('tags/slug/{slug}', [TagController::class, 'showBySlug']);

    Route::get('media', [MediaController::class, 'index']);
    Route::get('media/{id}', [MediaController::class, 'show'])->whereNumber('id');

    Route::get('settings', [SettingsController::class, 'index']);
});
