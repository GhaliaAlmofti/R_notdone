<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $school = auth()->user()->adminSchool;

        if (!$school) {
            return response()->json(['message' => 'No school assigned.'], 404);
        }

        return response()->json([
            'school' => $school,
            'stats' => [
                'pending_reviews' => $school->reviews()->where('status', 'pending_verification')->count(),
                'total_reviews' => $school->reviews()->count(),
            ]
        ]);
    }
}
