<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Main upload page
Route::get('/', [ImageController::class, 'index'])->name('images.index');

// Upload images (up to 5)
Route::post('/images/upload', [ImageController::class, 'upload'])->name('images.upload');

// Edit page for a specific image
Route::get('/images/{id}/edit', [ImageController::class, 'edit'])->name('images.edit');

// Rotate image 90 degrees
Route::post('/images/{id}/rotate', [ImageController::class, 'rotate'])->name('images.rotate');

// Crop image
Route::post('/images/{id}/crop', [ImageController::class, 'crop'])->name('images.crop');

// Undo last operation
Route::post('/images/{id}/undo', [ImageController::class, 'undo'])->name('images.undo');

// Save final image
Route::post('/images/{id}/save', [ImageController::class, 'save'])->name('images.save');

