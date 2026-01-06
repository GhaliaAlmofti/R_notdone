<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\SchoolPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolPhotoController extends Controller
{
    public function index()
    {
        $school = auth()->user()->adminSchool;

        abort_if(!$school, 403);

        $photos = $school->photos()->latest()->get();

        return view('school_admin.photos.index', compact('school', 'photos'));
    }

    public function store(Request $request)
    {
        $school = auth()->user()->adminSchool;

        abort_if(!$school, 403);

        $data = $request->validate([
            'photos'   => ['required', 'array', 'min:1'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        foreach ($data['photos'] as $file) {
            // store in: storage/app/public/schools/{school_id}/photos/...
            $path = $file->store("schools/{$school->id}/photos", 'public');

            $school->photos()->create([
                'path' => $path,
            ]);
        }

        return back()->with('success', __('Photos uploaded successfully.'));
    }

    public function destroy(SchoolPhoto $photo)
    {
        $school = auth()->user()->adminSchool;

        abort_if(!$school, 403);

        // ensure photo belongs to this admin's school
        abort_if($photo->school_id !== $school->id, 403);

        // delete file
        if ($photo->path && Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }

        $photo->delete();

        return back()->with('success', __('Photo deleted.'));
    }
}

