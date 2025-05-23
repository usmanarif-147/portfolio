<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('educations')->insert([
            [
                'institution' => 'Harvard University',
                'degree' => 'BSc Computer Science',
                'field_of_study' => 'Software Engineering',
                'start_year' => 2014,
                'end_year' => 2018,
                'description' => 'Learned fundamentals of CS.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'institution' => 'MIT',
                'degree' => 'MSc Artificial Intelligence',
                'field_of_study' => 'AI/ML',
                'start_year' => 2019,
                'end_year' => 2021,
                'description' => 'Specialized in deep learning.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
