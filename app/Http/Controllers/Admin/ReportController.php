<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function index(): JsonResponse
    {
        // Get reviews that have been reported (is_reported = true)
        $reportedReviews = Review::with(['school', 'user'])
            ->where('is_reported', true)
            ->latest()
            ->get();

        return response()->json([
            'reports' => $reportedReviews
        ]);
    }

    /**
     * Admin decides the report is INVALID (Review stays)
     */
    public function resolveInvalid(Review $review): JsonResponse
    {
        $review->update(['is_reported' => false]);

        return response()->json([
            'message' => 'Report dismissed. Review remains active.'
        ]);
    }

    /**
     * Admin decides the report is VALID (Review is deleted)
     */
    public function resolveValid(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json([
            'message' => 'Report resolved. Review has been deleted.'
        ]);
    }
}
