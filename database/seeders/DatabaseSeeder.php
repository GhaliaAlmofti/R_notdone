<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call Super Admin seeder
        $this->call(SuperAdminSeeder::class);
        // Call School seeder
        $this->call(SchoolSeeder::class);
        // Call School Admin seeder
        $this->call(SchoolAdminSeeder::class);

        
    }
}
