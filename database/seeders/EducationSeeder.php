<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        Education::create([
            'degree_title' => 'Bachelor of Science in Software Engineering',
            'institution' => 'University of Management and Technology (UMT), Lahore',
            'start_date' => '2016-02-28',
            'end_date' => '2021-02-28',
            'sort_order' => 0,
        ]);
    }
}
