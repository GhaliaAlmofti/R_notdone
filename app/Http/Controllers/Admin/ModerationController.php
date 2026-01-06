<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewModerationController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['school', 'parent'])
            ->where('status', 'pending_moderation')
            ->latest()
            ->paginate(15);

        return view('admin.reviews.moderation', compact('reviews'));
    }

    public function approve(Review $review)
    {
        abort_unless($review->status === 'pending_moderation', 404);

        $review->update([
            'status' => 'pending_verification',
            'moderated_at' => now(),
            'moderated_by' => auth()->id(),
        ]);

        return back()->with('success', __('Sent to school for verification.'));
    }

    public function reject(Request $request, Review $review)
    {
        abort_unless($review->status === 'pending_moderation', 404);

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $review->update([
            'status' => 'rejected',
            'moderated_at' => now(),
            'moderated_by' => auth()->id(),
            'rejection_reason' => $data['rejection_reason'],
        ]);

        return back()->with('success', __('Review rejected.'));
    }
}

