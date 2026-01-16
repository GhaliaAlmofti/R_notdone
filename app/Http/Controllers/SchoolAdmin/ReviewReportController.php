<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewReportController extends Controller
{
    public function store(Request $request, Review $review): JsonResponse
    {
        $school = auth()->user()->adminSchool;

        if (!$school || (int) $review->school_id !== (int) $school->id || $review->status !== 'approved') {
            return response()->json(['message' => 'Unauthorized or invalid review.'], 403);
        }

        if ($review->is_reported) {
            return response()->json(['message' => __('This review is already reported.')], 200);
        }

        $data = $request->validate([
            'report_reason' => ['required', 'string', 'max:500'],
        ]);

        $review->update([
            'is_reported'         => true,
            'report_reason'       => $data['report_reason'],
            'reported_at'         => now(),
            'reported_by'         => auth()->id(),
            'report_status'       => 'pending',
        ]);

        return response()->json([
            'message' => __('Thanks. Your report has been submitted.'),
            'review_status' => 'reported'
        ]);
    }
}
