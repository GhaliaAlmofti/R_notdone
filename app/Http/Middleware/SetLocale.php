<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
{
    // Priority: 1. Header from React, 2. Session, 3. Default
    $locale = $request->header('Accept-Language', session('locale', config('app.locale')));

    // Ensure it's just 'ar' or 'en'
    $locale = substr($locale, 0, 2);

    App::setLocale($locale);
    return $next($request);
}
}

