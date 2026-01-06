<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewReportController extends Controller
{
    public function store(Request $request, Review $review)
    {
        $school = auth()->user()->adminSchool;

        if (!$school) {
            abort(403);
        }

        // ✅ Only reviews of their school
        if ((int) $review->school_id !== (int) $school->id) {
            abort(403);
        }

        // ✅ Only published reviews
        if ($review->status !== 'approved') {
            abort(403);
        }

        // ✅ Don't overwrite an existing report
        if ($review->is_reported) {
            return back()->with('success', __('This review is already reported.'));
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
            'report_resolved_at'  => null,
            'report_resolved_by'  => null,
        ]);

        return back()
            ->with('success', __('Thanks. Your report has been submitted.'))
            ->with('tab', 'published'); // ✅ keep Published tab open
    }
}
