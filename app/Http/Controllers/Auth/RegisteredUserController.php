<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     * React will POST to this method.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],

            // phone is REQUIRED for Benghazi school parents
            'phone' => ['required', 'string', 'max:20'],

            // city optional
            'city' => ['nullable', 'string', 'max:255'],

            // must be checked in the React checkbox
            'terms_accepted' => ['accepted'],

            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'city' => $validated['city'] ?? null,
            'terms_accepted' => true,
            'role' => 'parent', // Force role to parent for public registration
            'password' => Hash::make($validated['password']),
        ]);

        // Trigger Laravel's built-in registration event (useful for emails)
        event(new Registered($user));

        // Log the user in on the server-side
        Auth::login($user);

        // Generate the token for the React Frontend to store
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
            'role' => $user->role,
        ], 201); // 201 means "Created"
    }
}
