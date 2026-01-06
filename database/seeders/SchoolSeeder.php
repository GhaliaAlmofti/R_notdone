<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Al Noor International School',
                'area' => 'Benghazi - Al-Fuwayhat',
                'category' => 'international',
                'level' => 'primary',
                'email' => 'info@alnoor.test',
                'phone' => '0912345678',
                'address' => 'Main Street, Al-Fuwayhat',
                'president_name' => 'Dr. Ahmed Ali',
                'fees_range' => '2000–6000 LYD',
                'gender_type' => 'mixed',
                'curriculum' => 'International',
            ],
            [
                'name' => 'Benghazi Private Academy',
                'area' => 'Benghazi - Al-Kish',
                'category' => 'private',
                'level' => 'secondary',
                'email' => 'contact@bpa.test',
                'phone' => '0923456789',
                'address' => 'Al-Kish District',
                'president_name' => 'Ms. Salwa Omar',
                'fees_range' => '1500–4000 LYD',
                'gender_type' => 'girls',
                'curriculum' => 'National',
            ],
            [
                'name' => 'Future Stars Kindergarten',
                'area' => 'Benghazi - Al-Sabre',
                'category' => 'private',
                'level' => 'kindergarten',
                'email' => 'hello@futurestars.test',
                'phone' => '0945678901',
                'address' => 'Near Park, Al-Sabre',
                'president_name' => 'Mr. Mustafa Nasser',
                'fees_range' => '800–2000 LYD',
                'gender_type' => 'mixed',
                'curriculum' => 'National',
            ],
        ];

        foreach ($schools as $data) {
            $slug = Str::slug($data['name']);

            School::updateOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'slug' => $slug,
                    'admin_user_id' => null, // keep null here
                ])
            );
        }
    }
}
