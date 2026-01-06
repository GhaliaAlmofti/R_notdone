<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ✅ Home → role-based redirect
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $role = auth()->user()->role;

    return match ($role) {
        'super_admin'  => redirect()->route('admin.reviews.moderation'),
        'school_admin' => redirect()->route('school_admin.profile.edit'),
        default        => redirect()->route('parent.schools.index'),
    };
})->name('home');

// Optional: keep /dashboard but redirect logically as well
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $role = auth()->user()->role;

    return match ($role) {
        'super_admin'  => redirect()->route('admin.reviews.moderation'),
        'school_admin' => redirect()->route('school_admin.profile.edit'),
        default        => redirect()->route('parent.schools.index'),
    };
})->middleware('auth')->name('dashboard');

// Breeze Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');


// ✅ Load clean role-based route files
require __DIR__ . '/auth.php';
require __DIR__ . '/parent.php';
require __DIR__ . '/school_admin.php';
require __DIR__ . '/admin.php';

