<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\FrontEndController;

Route::get('/', [FrontEndController::class, 'home']);
