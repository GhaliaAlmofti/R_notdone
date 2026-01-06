<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reported = Review::with(['school', 'user'])
            ->where('is_reported', true)
            ->latest('reported_at')
            ->get();

        return view('admin.reports.index', compact('reported'));
    }

    /**
     * Mark report as VALID
     * - Report is correct
     * - Review should be hidden / sent back to moderation
     */
    public function resolveValid(Review $review)
    {
        $review->update([
            'report_status'       => 'resolved_valid',
            'report_resolved_at'  => now(),
            'report_resolved_by'  => auth()->id(),

            // Send review back to moderation / hide it
            'status'              => 'pending', // or 'rejected' if you prefer
            'moderated_at'        => now(),
            'moderated_by'        => auth()->id(),
        ]);

        return back()->with('success', __('Report marked as valid.'));
    }

    /**
     * Mark report as INVALID
     * - Report is incorrect
     * - Review remains visible
     */
    public function resolveInvalid(Review $review)
    {
        $review->update([
            'report_status'       => 'resolved_invalid',
            'report_resolved_at'  => now(),
            'report_resolved_by'  => auth()->id(),

            // Clear report flags
            'is_reported'         => false,
            'report_reason'       => null,
            'reported_at'         => null,
            'reported_by'         => null,
        ]);

        return back()->with('success', __('Report marked as invalid.'));
    }

    /**
     * Legacy dismiss action (optional to keep)
     */
    public function dismiss(Review $review)
    {
        $review->update([
            'is_reported'   => false,
            'report_reason' => null,
            'reported_at'   => null,
            'reported_by'   => null,
        ]);

        return back()->with('success', __('Report dismissed.'));
    }

    /**
     * Remove review completely (hard moderation)
     */
    public function remove(Request $request, Review $review)
    {
        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $review->update([
            'status'             => 'rejected',
            'rejection_reason'   => $data['rejection_reason'],
            'moderated_at'       => now(),
            'moderated_by'       => auth()->id(),

            // Clear report
            'is_reported'        => false,
            'report_reason'      => null,
            'reported_at'        => null,
            'reported_by'        => null,
        ]);

        return back()->with('success', __('Review removed.'));
    }
}
