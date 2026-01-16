<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SchoolController extends Controller
{
    /**
     * Search page - returns JSON for React
     */
    public function index(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));
        $area = trim((string) $request->query('area', ''));
        $category = trim((string) $request->query('category', ''));
        $level = trim((string) $request->query('level', ''));

        $schools = collect();

        // Query logic stays the same
        if ($q !== '' || $area !== '' || $category !== '' || $level !== '') {
            $schools = School::query()
                ->when($q !== '', fn($query) => $query->where('name', 'like', "%{$q}%"))
                ->when($area !== '', fn($query) => $query->where('area', $area))
                ->when($category !== '', fn($query) => $query->where('category', $category))
                ->when($level !== '', fn($query) => $query->where('level', $level))
                ->withCount(['reviews as approved_reviews_count' => function ($query) {
                    $query->where('status', 'approved');
                }])
                ->withAvg(['reviews as avg_rating' => function ($query) {
                    $query->where('status', 'approved');
                }], 'overall_rating')
                ->orderBy('name')
                ->get();
        }

        // Dropdown options
        $areas = School::query()->select('area')->distinct()->orderBy('area')->pluck('area');
        $categories = School::query()->select('category')->distinct()->orderBy('category')->pluck('category');
        $levels = School::query()->select('level')->distinct()->orderBy('level')->pluck('level');

        // ✅ RETURN JSON FOR REACT
        return response()->json([
            'schools' => $schools,
            'filters' => [
                'areas' => $areas,
                'categories' => $categories,
                'levels' => $levels
            ],
            'meta' => [
                'query' => $q,
                'current_area' => $area,
                'current_category' => $category,
                'current_level' => $level,
            ]
        ]);
    }

    /**
     * School profile page
     */
    public function show(School $school): JsonResponse
    {
        // Load relationships and counts in one go for efficiency
        $school->load(['photos']);

        $approvedReviews = $school->reviews()
            ->with('user:id,name') // Include user name for the review card
            ->where('status', 'approved')
            ->latest()
            ->get();

        $avg = $school->reviews()
            ->where('status', 'approved')
            ->avg('overall_rating');

        // ✅ RETURN JSON FOR REACT
        return response()->json([
            'school' => $school,
            'approvedReviews' => $approvedReviews,
            'avgRating' => round((float)$avg, 1), // Format to 1 decimal place
        ]);
    }
}
