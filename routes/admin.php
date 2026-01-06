<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReviewModerationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SchoolManagementController;



Route::middleware(['web', 'auth', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard (navbar uses this)
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Moderation
        Route::get('/reviews/moderation', [ReviewModerationController::class, 'index'])
            ->name('reviews.moderation');

        Route::post('/reviews/{review}/approve', [ReviewModerationController::class, 'approve'])
            ->name('reviews.approve_moderation');

        Route::post('/reviews/{review}/reject', [ReviewModerationController::class, 'reject'])
            ->name('reviews.reject_moderation');

        Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

        Route::post('/reports/{review}/dismiss', [ReportController::class, 'dismiss'])
        ->name('reports.dismiss');

        Route::post('/reports/{review}/remove', [ReportController::class, 'remove'])
        ->name('reports.remove');

        // Schools management
        Route::get('/schools', [SchoolManagementController::class, 'index'])->name('schools.index');
        Route::get('/schools/create', [SchoolManagementController::class, 'create'])->name('schools.create');
        Route::post('/schools', [SchoolManagementController::class, 'store'])->name('schools.store');

        Route::get('/schools/{school}/assign-admin', [SchoolManagementController::class, 'assignAdminForm'])->name('schools.assign_admin_form');
        Route::post('/schools/{school}/assign-admin', [SchoolManagementController::class, 'assignAdmin'])->name('schools.assign_admin');

        // Edit / Update school
        Route::get('/schools/{school}/edit', [SchoolManagementController::class, 'edit'])
            ->name('schools.edit');

        Route::patch('/schools/{school}', [SchoolManagementController::class, 'update'])
            ->name('schools.update');

        // Delete school
        Route::delete('/schools/{school}', [SchoolManagementController::class, 'destroy'])
            ->name('schools.destroy');

        // Unassign admin from a school
        Route::post('/schools/{school}/unassign-admin', [SchoolManagementController::class, 'unassignAdmin'])
            ->name('schools.unassign_admin');

        Route::post('/reports/{review}/valid', [ReportController::class, 'resolveValid'])
            ->name('reports.valid');

        Route::post('/reports/{review}/invalid', [ReportController::class, 'resolveInvalid'])
            ->name('reports.invalid');


    });

