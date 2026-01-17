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
     * Confirm the user's password for React/API using plain-text.
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validate that the password was actually sent
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        // 2. Check if the plain-text password matches the authenticated user
        // We use a direct comparison instead of Auth::validate()
        if ($request->user()->password !== $request->password) {
            throw ValidationException::withMessages([
                'password' => [__('auth.password')],
            ]);
        }

        // 3. For SPA/React, store the confirmation in the session
        $request->session()->put('auth.password_confirmed_at', time());

        // 4. Return JSON success
        return response()->json([
            'message' => __('Password confirmed successfully.'),
            'confirmed' => true
        ]);
    }
}
