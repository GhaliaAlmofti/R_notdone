<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\SchoolPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class SchoolPhotoController extends Controller
{
    /**
     * Get all photos for the school admin's gallery.
     */
    public function index(): JsonResponse
    {
        $school = auth()->user()->adminSchool;

        if (!$school) {
            return response()->json(['message' => 'No school assigned.'], 403);
        }

        $photos = $school->photos()->latest()->get();

        return response()->json([
            'photos' => $photos
        ]);
    }

    /**
     * Upload photos via React.
     */
    public function store(Request $request): JsonResponse
    {
        $school = auth()->user()->adminSchool;

        if (!$school) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'photos'   => ['required', 'array', 'min:1'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB limit
        ]);

        $savedPhotos = [];

        foreach ($request->file('photos') as $file) {
            $path = $file->store("schools/{$school->id}/photos", 'public');

            $photo = $school->photos()->create([
                'path' => $path, // Make sure your DB column is 'path'
            ]);

            $savedPhotos[] = $photo;
        }

        return response()->json([
            'message' => __('Photos uploaded successfully.'),
            'photos' => $savedPhotos
        ], 201);
    }

    /**
     * Delete a photo.
     */
    public function destroy(SchoolPhoto $photo): JsonResponse
    {
        $school = auth()->user()->adminSchool;

        if (!$school || $photo->school_id !== $school->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($photo->path && Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }

        $photo->delete();

        return response()->json([
            'message' => __('Photo deleted successfully.')
        ]);
    }
}
