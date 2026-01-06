<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolAdmin\SchoolProfileController;
use App\Http\Controllers\SchoolAdmin\ReviewApprovalController;
use App\Http\Controllers\SchoolAdmin\ReviewReportController;

Route::middleware(['web', 'auth', 'role:school_admin'])
    ->prefix('school-admin')
    ->name('school_admin.')
    ->group(function () {

        // ✅ HOME: redirect /school-admin -> /school-admin/profile
        Route::get('/', function () {
            return redirect()->route('school_admin.profile.edit');
        })->name('home');

        // ✅ School profile management (HOME page)
        Route::get('/profile', [SchoolProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [SchoolProfileController::class, 'update'])
            ->name('profile.update');

        // ✅ Photos management (upload/delete) - stays under profile section
        Route::post('/photos', [SchoolProfileController::class, 'storePhotos'])
            ->name('photos.store');

        Route::delete('/photos/{photo}', [SchoolProfileController::class, 'destroyPhoto'])
            ->name('photos.destroy');

        // ✅ Verification (reviews pending verification)
        Route::get('/reviews', [ReviewApprovalController::class, 'index'])
            ->name('reviews.index');

        Route::post('/reviews/{review}/approve', [ReviewApprovalController::class, 'approve'])
            ->name('reviews.approve');

        Route::post('/reviews/{review}/reject', [ReviewApprovalController::class, 'reject'])
            ->name('reviews.reject');

        // ✅ School admin can report reviews of their school (approved only)
        Route::post('/reviews/{review}/report', [ReviewReportController::class, 'store'])
            ->name('reviews.report');
    });


