<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Read from .env (local/demo only)
        $superAdminPassword = env('DEMO_SUPER_ADMIN_PASSWORD');

        if (! $superAdminPassword) {
            $this->command?->warn(
                'DEMO_SUPER_ADMIN_PASSWORD is not set. Skipping Super Admin seeding.'
            );
            return;
        }

        User::updateOrCreate(
            ['email' => 'admin@searchreviews.test'],
            [
                'name' => 'Super Admin',
                'password' => $superAdminPassword, // Removed Hash::make()
                'role' => 'super_admin',
                // ... other fields
            ]
        );
    }
}
