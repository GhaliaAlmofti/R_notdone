<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Parent\SchoolController;
use App\Http\Controllers\Parent\ReviewController;
use App\Http\Controllers\Parent\ReviewReportController;
use App\Http\Controllers\SchoolAdmin\SchoolProfileController;
use App\Http\Controllers\SchoolAdmin\SchoolPhotoController;
use App\Http\Controllers\SchoolAdmin\ReviewApprovalController;
use App\Http\Controllers\Admin\SchoolManagementController;
use App\Http\Controllers\Admin\ReviewModerationController;

// --- PUBLIC ROUTES ---
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/schools', [SchoolController::class, 'index']);
Route::get('/schools/{school:slug}', [SchoolController::class, 'show']);

// --- PROTECTED ROUTES (Requires Token) ---
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::get('/user', fn(Request $request) => $request->user());

    // --- PARENT ACCESS ---
    Route::middleware('role:parent')->group(function () {
        Route::post('/schools/{school:slug}/reviews', [ReviewController::class, 'store']);
        Route::get('/my-reviews', [ReviewController::class, 'myReviews']);
        Route::post('/reviews/{review}/report', [ReviewReportController::class, 'store']);
    });

    // --- SCHOOL ADMIN ACCESS ---
    Route::middleware('role:school_admin')->prefix('school-admin')->group(function () {
        // Profile logic
        Route::get('/profile', [SchoolProfileController::class, 'edit']);
        Route::patch('/profile', [SchoolProfileController::class, 'update']);

        // Photo Gallery logic (Now pointing to SchoolPhotoController)
        Route::get('/photos', [SchoolPhotoController::class, 'index']);
        Route::post('/photos', [SchoolPhotoController::class, 'store']);
        Route::delete('/photos/{photo}', [SchoolPhotoController::class, 'destroy']);

        // Review moderation logic
        Route::get('/reviews', [ReviewApprovalController::class, 'index']);
        Route::post('/reviews/{review}/approve', [ReviewApprovalController::class, 'approve']);
        Route::post('/reviews/{review}/reject', [ReviewApprovalController::class, 'reject']);
    });

    // --- SUPER ADMIN ACCESS ---
    Route::middleware('role:super_admin')->prefix('admin')->group(function () {
        // Moderation
        Route::get('/moderation', [ReviewModerationController::class, 'index']);
        Route::post('/reviews/{review}/approve', [ReviewModerationController::class, 'approve']);
        Route::post('/reviews/{review}/reject', [ReviewModerationController::class, 'reject']);

        // School Management
        Route::apiResource('schools', SchoolManagementController::class);
        Route::post('/schools/{school}/assign-admin', [SchoolManagementController::class, 'assignAdmin']);
    });
});
