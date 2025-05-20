<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

Route::resource('blog', BlogController::class);
