<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PostController;

Route::get('', [PostController::class, 'index'])->name('web.post.index');
