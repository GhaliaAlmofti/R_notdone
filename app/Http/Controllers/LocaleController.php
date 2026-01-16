<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocaleController
{
    public function switch(Request $request, string $locale): JsonResponse
{
    $allowed = ['en', 'ar'];
    if (!in_array($locale, $allowed, true)) {
        return response()->json(['message' => 'Invalid locale'], 400);
    }

    // Store in session for mixed-mode or just return success
    session(['locale' => $locale]);

    return response()->json([
        'message' => 'Language switched successfully',
        'locale' => $locale
    ]);
}
}
