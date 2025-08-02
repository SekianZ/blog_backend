<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\ProfileController;
//POST---------------------------------------------------------------------------------------------------------------

// 🔓 Rutas públicas (no requieren autenticación)
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');


// 🔐 Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/update/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/user', [PostController::class, 'getPostsByAuthenticatedUser'])->name('posts.getPostsByAuthenticatedUser');
});
//--------------------------------------------------------------------------------------------------------------------

//COMMENTS-------------------------------------------------------------------------------------------------------------

// 🔓 Rutas públicas (no requieren autenticación)
Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
Route::get('/comments/{comment}', [CommentController::class, 'show'])->name('comments.show');

//Obtener comentarios de un post
Route::get('/comments/{post}/comments', [CommentController::class, 'getCommentsPost'])->name('comments.getCommentsPost');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/comments/{post}', [CommentController::class, 'store'])->name(name: 'comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name(name: 'comments.destroy');
});

//LIKES-------------------------------------------------------------------------------------------------------------

// 🔓 Rutas públicas (no requieren autenticación)

//Obtener likes de un post 
Route::get('/likes/{post}', [LikeController::class, 'index'])->name('likes.index');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/likes/{post}', [LikeController::class, 'alternateLike'])->name('likes.alternateLike');
});


//PROFILE---------------------------------------------------------------------------------------------------------------
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'updateProfile']); // PATCH para actualizaciones parciales
    Route::post('/profile/image', [ProfileController::class, 'storeProfileImage']);
    Route::delete('/profile/user', [ProfileController::class, 'destroy']);
});