<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

class ReviewModerationController extends Controller
{
    /**
     * List all reviews pending moderation
     */
    public function index(): JsonResponse
    {
        $reviews = Review::with(['school', 'user'])
            ->where('status', 'pending_moderation')
            ->latest()
            ->paginate(20);

        return response()->json($reviews);
    }

    /**
     * Move review from Super Admin -> School Admin
     */
    public function approve(Review $review): JsonResponse
    {
        $review->update(['status' => 'pending_verification']);

        return response()->json([
            'message' => 'Review approved and sent to School Admin for verification.',
            'review' => $review
        ]);
    }

    /**
     * Reject and delete review
     */
    public function reject(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json([
            'message' => 'Review has been rejected and removed.'
        ]);
    }
}
