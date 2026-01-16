<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SchoolManagementController extends Controller
{
    /**
     * Display a listing of schools.
     * React will fetch this to show in a Table.
     */
    public function index(): JsonResponse
    {
        $schools = School::with('admin')->latest()->paginate(10);
        return response()->json($schools);
    }

    /**
     * Store a newly created school.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'gender_type' => ['required', 'in:boys,girls,mixed'],
            // ... other fields ...
        ]);

        // Slug Logic
        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 2;
        while (School::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        $school = School::create(array_merge($data, [
            'slug' => $slug,
            'admin_user_id' => null,
        ]));

        return response()->json([
            'message' => __('School created. Please assign an admin.'),
            'school' => $school
        ], 201);
    }

    /**
     * Show a single school for the "Edit" form in React.
     */
    public function show(School $school): JsonResponse
    {
        return response()->json($school->load('admin'));
    }

    /**
     * Assign an admin to a school.
     */
    public function assignAdmin(Request $request, School $school): JsonResponse
    {
        $data = $request->validate([
            'existing_admin_id' => ['nullable', 'exists:users,id'],
            'new_admin_name' => ['nullable', 'string', 'max:255'],
            'new_admin_email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'new_admin_password' => ['nullable', 'string', 'min:8'],
        ]);

        $admin = null;

        // 1. Logic for existing admin
        if (!empty($data['existing_admin_id'])) {
            $admin = User::findOrFail($data['existing_admin_id']);
        }
        // 2. Logic for creating new admin
        elseif (!empty($data['new_admin_email'])) {
            $admin = User::create([
                'name' => $data['new_admin_name'],
                'email' => $data['new_admin_email'],
                'password' => Hash::make($data['new_admin_password']),
                'role' => 'school_admin',
                'terms_accepted' => true,
            ]);
        }

        if (!$admin) {
            return response()->json(['error' => 'No admin provided'], 422);
        }

        $school->update(['admin_user_id' => $admin->id]);

        return response()->json([
            'message' => __('Admin assigned successfully.'),
            'school' => $school->load('admin')
        ]);
    }

    /**
     * Update the school details.
     */
    public function update(Request $request, School $school): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string'],
            // ... other fields ...
        ]);

        if ($data['name'] !== $school->name) {
            $data['slug'] = Str::slug($data['name']); // Simple re-slug
        }

        $school->update($data);

        return response()->json([
            'message' => __('School updated successfully.'),
            'school' => $school
        ]);
    }

    /**
     * Remove the school.
     */
    public function destroy(School $school): JsonResponse
    {
        $school->delete();
        return response()->json(['message' => __('School deleted successfully.')]);
    }
}
