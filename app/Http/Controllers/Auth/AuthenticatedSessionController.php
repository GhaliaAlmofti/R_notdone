<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        // This will write the result to storage/logs/laravel.log
        \Illuminate\Support\Facades\Log::info('Login Attempt Details:', [
            'typed_email' => $request->email,
            'typed_password' => $request->password,
            'db_password' => $user ? $user->password : 'USER NOT FOUND',
            'match' => $user ? ($request->password === $user->password) : false,
        ]);

        if (! $user || $request->password !== $user->password) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        \Illuminate\Support\Facades\Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['user' => $user]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }
}
