<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewModerationController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['school', 'user'])
            ->where('status', 'pending_moderation')
            ->latest()
            ->get();

        return view('admin.reviews.moderation', compact('reviews'));
    }

    public function approve(Review $review)
    {
        if ($review->status !== 'pending_moderation') {
            abort(403);
        }

        $review->update([
            'status' => 'pending_verification',
            'moderated_at' => now(),
            'moderated_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        return back()->with(
            'success',
            __('Sent to school admin for verification.')
        );
    }

    public function reject(Request $request, Review $review)
    {
        if ($review->status !== 'pending_moderation') {
            abort(403);
        }

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
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
