<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewApprovalController extends Controller
{
    public function index(): JsonResponse
    {
        $school = auth()->user()->adminSchool;
        abort_unless($school, 403);

        $reviews = Review::with(['user'])
            ->where('school_id', $school->id)
            ->where('status', 'pending_verification')
            ->latest()->get();

        return response()->json($reviews);
    }

    public function approve(Review $review): JsonResponse
    {
        $this->authorizeSchool($review);

        $review->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        return response()->json(['message' => __('Review verified and published.')]);
    }

    public function reject(Request $request, Review $review): JsonResponse
    {
        $this->authorizeSchool($review);

        if ($review->status !== 'pending_verification') {
            return response()->json(['message' => __('This review is not pending verification.')], 422);
        }

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $review->update([
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'rejection_reason' => $data['rejection_reason'],
        ]);

        return response()->json(['message' => __('Review rejected.')]);
    }

    private function authorizeSchool(Review $review): void
    {
        $school = auth()->user()->adminSchool;
        abort_unless($school && (int) $review->school_id === (int) $school->id, 403);
    }
}
