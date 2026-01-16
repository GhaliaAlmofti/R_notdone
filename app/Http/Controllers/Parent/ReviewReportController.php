<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewReportController extends Controller
{
    public function store(Request $request, Review $review): JsonResponse
    {
        if ($review->status !== 'approved') {
            return response()->json(['message' => 'Review not found or not active.'], 404);
        }

        $data = $request->validate([
            'report_reason' => ['required', 'string', 'max:500'],
        ]);

        if ($review->user_id === auth()->id()) {
            return response()->json(['message' => __('You cannot report your own review.')], 403);
        }

        $review->update([
            'is_reported'         => true,
            'report_reason'       => $data['report_reason'],
            'reported_at'         => now(),
            'reported_by'         => auth()->id(),
            'report_status'       => 'pending',
        ]);

        return response()->json(['message' => __('Thanks. Your report has been submitted.')]);
    }
}
