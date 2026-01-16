<?php

namespace App\Http\Controllers\Parent;
use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * Store a new review.
     * React will call this via Axios.post(`/api/schools/${slug}/reviews`)
     */
    public function store(Request $request, School $school): JsonResponse
    {
        // 1. Validation (Errors automatically return as 422 JSON)
        $validated = $request->validate([
            'student_number' => ['required', 'string', 'max:50'],
            'hygiene' => ['required', 'integer', 'min:0', 'max:5'],
            'management' => ['required', 'integer', 'min:0', 'max:5'],
            'education_quality' => ['required', 'integer', 'min:0', 'max:5'],
            'parent_communication' => ['required', 'integer', 'min:0', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        // 2. Duplicate Check
        $already = Review::where('school_id', $school->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($already) {
            return response()->json([
                'message' => __('You already submitted a review for this school.'),
                'errors' => ['student_number' => [__('Duplicate review detected.')]]
            ], 403); // 403 Forbidden
        }

        // 3. Calculation
        $overall = (
            $validated['hygiene'] +
            $validated['management'] +
            $validated['education_quality'] +
            $validated['parent_communication']
        ) / 4;

        // 4. Create Review
        $review = Review::create([
            'school_id' => $school->id,
            'user_id' => auth()->id(),
            'student_number' => $validated['student_number'],
            'hygiene' => $validated['hygiene'],
            'management' => $validated['management'],
            'education_quality' => $validated['education_quality'],
            'parent_communication' => $validated['parent_communication'],
            'overall_rating' => round($overall, 1),
            'comment' => $validated['comment'] ?? null,
            'status' => 'pending_moderation',
        ]);

        // 5. Return JSON Success (No redirect!)
        return response()->json([
            'message' => __('Review submitted. Waiting for review moderation.'),
            'review' => $review
        ], 201); // 201 Created
    }

    /**
     * Get logged-in user's reviews.
     * React will call this for the "My Reviews" page
     */
    public function myReviews(): JsonResponse
    {
        $reviews = Review::with('school:id,name,slug,logo_path') // Get school info too
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }
}
