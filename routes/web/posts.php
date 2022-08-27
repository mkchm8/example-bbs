<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\CommentController;

Route::prefix('posts')->group(function () {
    Route::get('', [PostController::class, 'index'])->name('web.post.index');

    Route::prefix('/{post_id}')->where(['post_id' => '[0-9]{1,8}'])->group(function () {
        Route::prefix('/comments')->group(function () {
            Route::get('', [CommentController::class, 'create'])->name('web.post.comment.create');
            Route::post('', [CommentController::class, 'store'])->name('web.post.comment.store');
        });
    });
});
