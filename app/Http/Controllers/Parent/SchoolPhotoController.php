<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\School;

class SchoolPhotoController extends Controller
{
    public function index(School $school)
    {
        // Only show photos publicly (no auth required)
        $photos = $school->photos()->latest()->get();

        return view('parent.schools.photos', compact('school', 'photos'));
    }
}


