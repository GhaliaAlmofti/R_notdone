<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewApprovalController extends Controller
{
    public function index()
    {
        $school = auth()->user()->adminSchool;

        abort_unless($school, 403);

        $reviews = Review::with(['school', 'user'])
            ->where('school_id', $school->id)
            ->where('status', 'pending_verification')
            ->latest()
            ->get();

        return view('school_admin.reviews.index', compact('school', 'reviews'));
    }

    public function approve(Review $review)
    {
        $this->authorizeSchool($review);

        if ($review->status !== 'pending_verification') {
            return back()->with('error', __('This review is not pending verification.'));
        }

        $review->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', __('Review verified and published.'));
    }

    public function reject(Request $request, Review $review)
    {
        $this->authorizeSchool($review);

        if ($review->status !== 'pending_verification') {
            return back()->with('error', __('This review is not pending verification.'));
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

        return back()->with('success', __('Review rejected.'));
    }

    private function authorizeSchool(Review $review): void
    {
        $school = auth()->user()->adminSchool;

        abort_unless($school, 403);

        if ((int) $review->school_id !== (int) $school->id) {
            abort(403);
        }
    }
}



