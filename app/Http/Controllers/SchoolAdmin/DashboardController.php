<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $school = auth()->user()->adminSchool;

        return view('school_admin.dashboard', compact('school'));
    }
}

