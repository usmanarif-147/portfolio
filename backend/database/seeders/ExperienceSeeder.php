<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('experiences')->insert([
            [
                'company_name' => 'TechCorp Inc.',
                'position' => 'Backend Developer',
                'location' => 'San Francisco',
                'start_date' => '2018-06-01',
                'end_date' => '2020-12-31',
                'is_current' => false,
                'description' => 'Built REST APIs using Laravel.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'DevStudio',
                'position' => 'Full Stack Developer',
                'location' => 'Remote',
                'start_date' => '2021-01-01',
                'end_date' => null,
                'is_current' => true,
                'description' => 'Leading frontend and backend dev using Vue + Laravel.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
