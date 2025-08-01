<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

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

//LIKES-------------------------------------------------------------------------------------------------------------

// 🔓 Rutas públicas (no requieren autenticación)

//Obtener likes de un post 
Route::get('/likes/{post}', [LikeController::class, 'index'])->name('likes.index');