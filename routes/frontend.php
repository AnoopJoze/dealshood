<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\FrontEndController;

Route::get('/', [FrontEndController::class, 'home']);
Route::get('/post-details', [FrontEndController::class, 'postDetail'])->name('post-details');
