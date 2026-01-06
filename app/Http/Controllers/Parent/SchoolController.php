<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    // Search page
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $area = trim((string) $request->query('area', ''));
        $category = trim((string) $request->query('category', ''));
        $level = trim((string) $request->query('level', ''));

        $schools = collect();

        // IMPORTANT: no results until user searches anything
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

        // dropdown options from DB (simple + clean)
        $areas = School::query()->select('area')->distinct()->orderBy('area')->pluck('area');
        $categories = School::query()->select('category')->distinct()->orderBy('category')->pluck('category');
        $levels = School::query()->select('level')->distinct()->orderBy('level')->pluck('level');

        return view('parent.schools.index', compact('schools', 'q', 'area', 'category', 'level', 'areas', 'categories', 'levels'));
    }

    // School profile page
    public function show(School $school)
    {
        $approvedReviews = $school->reviews()
            ->where('status', 'approved')
            ->latest()
            ->get();

        $avg = $school->reviews()
            ->where('status', 'approved')
            ->avg('overall_rating');

        return view('parent.schools.show', [
            'school' => $school,
            'approvedReviews' => $approvedReviews,
            'avgRating' => $avg,
        ]);
    }
}

