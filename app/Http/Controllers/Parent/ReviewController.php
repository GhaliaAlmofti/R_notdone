<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(School $school)
    {
        return view('parent.reviews.create', compact('school'));
    }

    public function store(Request $request, School $school)
    {
        $validated = $request->validate([
            'student_number' => ['required', 'string', 'max:50'],
            'hygiene' => ['required', 'integer', 'min:0', 'max:5'],
            'management' => ['required', 'integer', 'min:0', 'max:5'],
            'education_quality' => ['required', 'integer', 'min:0', 'max:5'],
            'parent_communication' => ['required', 'integer', 'min:0', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $already = Review::where('school_id', $school->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($already) {
            return back()
                ->withErrors([
                    'student_number' => __('You already submitted a review for this school.')
                ])
                ->withInput();
        }

        // ✅ Calculate overall rating
        $overall = (
            $validated['hygiene'] +
            $validated['management'] +
            $validated['education_quality'] +
            $validated['parent_communication']
        ) / 4;

        Review::create([
            'school_id' => $school->id,
            'user_id' => auth()->id(),
            'student_number' => $validated['student_number'],

            'hygiene' => $validated['hygiene'],
            'management' => $validated['management'],
            'education_quality' => $validated['education_quality'],
            'parent_communication' => $validated['parent_communication'],

            // ✅ stored once, no recalculation needed later
            'overall_rating' => round($overall, 1),

            'comment' => $validated['comment'] ?? null,

            // workflow start
            'status' => 'pending_moderation',
        ]);

        return redirect()
            ->route('parent.schools.show', $school)
            ->with(
                'success',
                __('Review submitted. Waiting for review moderation.')
            );
    }

    public function myReviews()
    {
        $reviews = Review::with('school')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('parent.reviews.my', compact('reviews'));
    }
}
