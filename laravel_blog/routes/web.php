<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

Route::get('/', [BlogController::class, 'index']);

// CRUD для блога
Route::resource('blog', BlogController::class);

// Импорт из CSV
Route::get('blog/import', [BlogController::class, 'importForm'])->name('blog.importForm');
Route::post('blog/import', [BlogController::class, 'import'])->name('blog.import');
