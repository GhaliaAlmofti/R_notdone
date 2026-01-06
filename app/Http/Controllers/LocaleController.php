<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        $allowed = ['en', 'ar'];

        if (!in_array($locale, $allowed, true)) {
            abort(404);
        }

        session(['locale' => $locale]);

        return back();
    }
}
