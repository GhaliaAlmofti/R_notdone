<?php

use Illuminate\Support\Facades\Route;

// This ensures that if a user goes to /schools or /dashboard in their browser,
// Laravel serves the React app instead of a 404 error.
Route::get('/{any}', function () {
    return view('app'); // Ensure you have a file at resources/views/app.blade.php
})->where('any', '.*');
