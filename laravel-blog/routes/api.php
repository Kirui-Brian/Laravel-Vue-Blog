<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostTagController;

// Authors Routes
Route::prefix('authors')->group(function () {
    Route::get('/', [AuthorController::class, 'index']); // Get all authors
    Route::get('{id}', [AuthorController::class, 'show']); // Get a single author
    Route::get('{id}/posts', [PostController::class, 'getAuthorPosts']); // Get posts by author ID
    Route::post('/', [AuthorController::class, 'store']); // Create a new author
    Route::put('{id}', [AuthorController::class, 'update']); // Update an existing author
    Route::delete('{id}', [AuthorController::class, 'destroy']); // Delete an author
});

// Posts Routes
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']); // Get all posts
    Route::get('{id}', [PostController::class, 'show']); // Get a single post
    Route::post('/', [PostController::class, 'store']); // Create a new post
    Route::put('{id}', [PostController::class, 'update']); // Update an existing post
    Route::delete('{id}', [PostController::class, 'destroy']); // Delete a post
});

// Tags Routes
Route::prefix('tags')->group(function () {
    Route::get('/', [TagController::class, 'index']); // Get all tags
    Route::get('{id}', [TagController::class, 'show']); // Get a single tag
    Route::post('/', [TagController::class, 'store']); // Create a new tag
    Route::put('{id}', [TagController::class, 'update']); // Update an existing tag
    Route::delete('{id}', [TagController::class, 'destroy']); // Delete a tag
});

// Post-Tag Relationships Routes
Route::prefix('posts/{postId}/tags')->group(function () {
    Route::post('{tagId}', [PostTagController::class, 'attach']); // Attach a tag to a post
    Route::delete('{tagId}', [PostTagController::class, 'detach']); // Detach a tag from a post
});
