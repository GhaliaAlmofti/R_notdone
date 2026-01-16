<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\JsonResponse;

class SchoolPhotoController extends Controller
{
    public function index(School $school): JsonResponse
    {
        $photos = $school->photos()->latest()->get();

        return response()->json([
            'school_name' => $school->name,
            'photos' => $photos
        ]);
    }
}
