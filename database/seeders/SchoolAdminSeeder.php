<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\School;

class SchoolAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'email' => 'admin.noor@test.com',
                'school_name' => 'Al Noor International School',
            ],
            [
                'email' => 'admin.bpa@test.com',
                'school_name' => 'Benghazi Private Academy',
            ],
        ];

        // Load password from .env
        $schoolAdminPassword = env('DEMO_SCHOOL_ADMIN_PASSWORD');

        if (! $schoolAdminPassword) {
            $this->command?->warn('DEMO_SCHOOL_ADMIN_PASSWORD is not set. Skipping school admin seeding.');
            return;
        }

        foreach ($admins as $data) {

            // 1) Find the school
            $school = School::where('name', $data['school_name'])->first();

            if (! $school) {
                $this->command?->warn("School not found: {$data['school_name']}");
                continue;
            }

            // 2) Create/update the admin user
            $admin = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'            => 'School Admin - ' . $school->name,
                    'password'        => $schoolAdminPassword, // Removed Hash::make()
                    'role'            => 'school_admin',
                    'phone'           => '0910000000',
                    'city'            => 'Benghazi',
                    'terms_accepted'  => true,
                ]
            );

            // 3) Assign the admin to the school
            $school->update([
                'admin_user_id' => $admin->id,
            ]);
        }
    }
}
