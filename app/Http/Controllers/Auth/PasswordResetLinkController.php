<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse; // Changed
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): JsonResponse // Changed return type
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Laravel sends the email in the background
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => 'success',
                'message' => __($status) // "We have emailed your password reset link."
            ], 200);
        }

        // If it fails (e.g. user not found), return a 422 error for React to show
        return response()->json([
            'errors' => [
                'email' => [__($status)]
            ]
        ], 422);
    }
}
