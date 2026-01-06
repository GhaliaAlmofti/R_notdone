<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSchoolRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SchoolPhoto;

class SchoolProfileController extends Controller
{
    public function edit()
    {
        $school = auth()->user()->adminSchool;

        if (!$school) {
            abort(404);
        }

        $photos = $school->photos()->latest()->get();

        // ✅ Published (approved) reviews for THIS school only, newest first
        $publishedReviews = $school->reviews()
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('school_admin.school.edit', compact('school', 'photos', 'publishedReviews'));
    }

    public function update(UpdateSchoolRequest $request)
    {
        $school = auth()->user()->adminSchool;

        if (!$school) {
            abort(404);
        }

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($school->logo_path && Storage::disk('public')->exists($school->logo_path)) {
                Storage::disk('public')->delete($school->logo_path);
            }

            $data['logo_path'] = $request->file('logo')
                ->store('uploads/schools/logos', 'public');
        }

        unset($data['admin_user_id']);

        $school->update($data);

        return back()->with('success', __('School profile updated successfully.'));
    }

    public function storePhotos(Request $request)
    {
        $school = auth()->user()->adminSchool;

        if (!$school) {
            abort(404);
        }

        $request->validate([
            'photos'   => 'required|array',
            'photos.*' => 'image|max:2048',
        ]);

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('uploads/schools/photos', 'public');

            $school->photos()->create([
                'path' => $path,
            ]);
        }

        return back()
            ->with('success', __('Photos uploaded successfully.'))
            ->with('tab', 'photos');
    }

    public function destroyPhoto(SchoolPhoto $photo)
    {
        $school = auth()->user()->adminSchool;

        if (!$school || $photo->school_id !== $school->id) {
            abort(403);
        }

        if (Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }

        $photo->delete();

        return back()
            ->with('success', __('Photo deleted.'))
            ->with('tab', 'photos');
    }
}
