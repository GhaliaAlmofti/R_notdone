<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ConfirmablePasswordController extends Controller
{
    /**
     * Confirm the user's password for React/API.
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validate that the password was actually sent
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        // 2. Check if the password matches the authenticated user
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            // Return a 422 Unprocessable Entity error (standard for validation failure)
            throw ValidationException::withMessages([
                'password' => [__('auth.password')],
            ]);
        }

        // 3. For SPA/React, we can store the confirmation in the session
        // or return a temporary signed status.
        $request->session()->put('auth.password_confirmed_at', time());

        // 4. Return JSON success so React knows to proceed
        return response()->json([
            'message' => __('Password confirmed successfully.'),
            'confirmed' => true
        ]);
    }
}
