<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        Education::create([
            'degree_title' => 'B.S. Software Engineering',
            'institution' => 'University of Management and Technology (UMT)',
            'start_date' => '2016-09-01',
            'end_date' => '2021-06-01',
            'sort_order' => 0,
        ]);
    }
}
