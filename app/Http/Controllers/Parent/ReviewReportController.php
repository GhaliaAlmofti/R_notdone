<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewReportController extends Controller
{
    public function store(Request $request, Review $review)
    {
        // Only allow reporting approved (public) reviews
        if ($review->status !== 'approved') {
            abort(403);
        }

        $data = $request->validate([
            'report_reason' => ['required', 'string', 'max:500'],
        ]);

        // Prevent reporting your own review
        if ($review->user_id === auth()->id()) {
            return back()->withErrors([
                'report_reason' => __('You cannot report your own review.'),
            ]);
        }

        $review->update([
            'is_reported'         => true,
            'report_reason'       => $data['report_reason'],
            'reported_at'         => now(),
            'reported_by'         => auth()->id(),
            'report_status'       => 'pending',
            'report_resolved_at'  => null,
            'report_resolved_by'  => null,
        ]);

        return back()->with('success', __('Thanks. Your report has been submitted.'));
    }
}
