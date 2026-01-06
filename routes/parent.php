<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Parent\SchoolController;
use App\Http\Controllers\Parent\ReviewController;
use App\Http\Controllers\Parent\ReviewReportController;
use App\Http\Controllers\Parent\SchoolPhotoController;



// Public: search + show school profile
Route::get('/schools', [SchoolController::class, 'index'])->name('parent.schools.index');
Route::get('/schools/{school:slug}', [SchoolController::class, 'show'])->name('parent.schools.show');

// Auth only: create + store review
Route::middleware(['auth'])->group(function () {
    Route::get('/schools/{school:slug}/reviews/create', [ReviewController::class, 'create'])
        ->name('parent.reviews.create');

    Route::post('/schools/{school:slug}/reviews', [ReviewController::class, 'store'])
        ->name('parent.reviews.store');

    Route::post('/reviews/{review}/report', [ReviewReportController::class, 'store'])
    ->name('parent.reviews.report');

    Route::get('/schools/{school:slug}/photos', [SchoolPhotoController::class, 'index'])
    ->name('parent.schools.photos');

    Route::get('/my-reviews', [ReviewController::class, 'myReviews'])
    ->name('parent.reviews.my');



});
