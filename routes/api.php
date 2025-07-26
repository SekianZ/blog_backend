<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//POST---------------------------------------------------------------------------------------------------------------

// 🔓 Rutas públicas (no requieren autenticación)
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// 🔐 Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {

});
//--------------------------------------------------------------------------------------------------------------------

//COMMENTS-------------------------------------------------------------------------------------------------------------

// 🔓 Rutas públicas (no requieren autenticación)
Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
Route::get('/comments/{comment}', [CommentController::class, 'show'])->name('comments.show');
Route::get('/comments/{post}/comments', [CommentController::class, 'getCommentsPost'])->name('comments.getCommentsPost');