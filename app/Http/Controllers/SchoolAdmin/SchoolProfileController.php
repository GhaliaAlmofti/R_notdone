<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class SchoolProfileController extends Controller
{
    /**
     * Get the school profile for the React form.
     */
    public function edit(): JsonResponse
    {
        // Use 'adminSchool' relationship for consistency
        $school = auth()->user()->adminSchool;

        if (!$school) {
            return response()->json(['message' => 'No school assigned to this admin.'], 404);
        }

        // Load photos so React can show the current gallery
        $school->load('photos');

        return response()->json([
            'school' => $school
        ]);
    }

    /**
     * Update school profile (Text fields + Logo)
     */
    public function update(Request $request): JsonResponse
    {
        $school = auth()->user()->adminSchool;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($school->logo_path) {
                Storage::disk('public')->delete($school->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $school->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'school' => $school->fresh(['photos'])
        ]);
    }
}
