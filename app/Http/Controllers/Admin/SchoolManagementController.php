<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SchoolManagementController extends Controller
{
    public function index()
    {
        $schools = School::with('admin')->latest()->paginate(10);
        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'president_name' => ['nullable', 'string', 'max:255'],
            'fees_range' => ['nullable', 'string', 'max:255'],
            'gender_type' => ['required', 'in:boys,girls,mixed'],
            'curriculum' => ['nullable', 'string', 'max:255'],
        ]);

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

        return redirect()
            ->route('admin.schools.assign_admin_form', $school)
            ->with('success', __('School created. Now assign an admin.'));
    }

    public function assignAdminForm(School $school)
    {
        $admins = User::where('role', 'school_admin')
            ->orderBy('name')
            ->get();

        return view('admin.schools.assign-admin', compact('school', 'admins'));
    }

    public function assignAdmin(Request $request, School $school)
    {
        $data = $request->validate([
            'existing_admin_id' => ['nullable', 'exists:users,id'],
            'new_admin_name' => ['nullable', 'string', 'max:255'],
            'new_admin_email' => ['nullable', 'email', 'max:255'],
            'new_admin_password' => ['nullable', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:255'],
        ]);

        $admin = null;

        if (!empty($data['existing_admin_id'])) {
            $admin = User::find($data['existing_admin_id']);

            if ($admin->role !== 'school_admin') {
                abort(403);
            }
        }

        if (!$admin) {
            if (
                empty($data['new_admin_name']) ||
                empty($data['new_admin_email']) ||
                empty($data['new_admin_password'])
            ) {
                return back()->withErrors([
                    'new_admin_email' => __('Choose an existing admin or create a new admin (name, email, password).'),
                ])->withInput();
            }

            $admin = User::create([
                'name' => $data['new_admin_name'],
                'email' => $data['new_admin_email'],
                'password' => Hash::make($data['new_admin_password']),
                'role' => 'school_admin',
                'phone' => $data['phone'] ?? null,
                'city' => $data['city'] ?? null,
                'terms_accepted' => true,
            ]);
        }

        // One admin → one school rule
        $alreadyAssigned = School::where('admin_user_id', $admin->id)
            ->where('id', '!=', $school->id)
            ->exists();

        if ($alreadyAssigned) {
            return back()->withErrors([
                'existing_admin_id' => __('This admin is already assigned to another school.'),
            ])->withInput();
        }

        $school->update([
            'admin_user_id' => $admin->id,
        ]);

        return redirect()
            ->route('admin.schools.index')
            ->with('success', __('School admin assigned successfully.'));
    }

    /* ============================================================
       =============== ADDED METHODS (AS REQUESTED) ================
       ============================================================ */

    public function edit(School $school)
    {
        return view('admin.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'president_name' => ['nullable', 'string', 'max:255'],
            'fees_range' => ['nullable', 'string', 'max:255'],
            'gender_type' => ['required', 'in:boys,girls,mixed'],
            'curriculum' => ['nullable', 'string', 'max:255'],
        ]);

        if ($data['name'] !== $school->name) {
            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug;
            $i = 2;

            while (
                School::where('slug', $slug)
                    ->where('id', '!=', $school->id)
                    ->exists()
            ) {
                $slug = $baseSlug . '-' . $i;
                $i++;
            }

            $data['slug'] = $slug;
        }

        $school->update($data);

        return redirect()
            ->route('admin.schools.index')
            ->with('success', __('School updated successfully.'));
    }

    public function destroy(School $school)
    {
        $school->delete();

        return redirect()
            ->route('admin.schools.index')
            ->with('success', __('School deleted successfully.'));
    }

    public function unassignAdmin(School $school)
    {
        $school->update(['admin_user_id' => null]);

        return back()->with('success', __('School admin removed from this school.'));
    }
}
