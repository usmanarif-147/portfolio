<?php

namespace Database\Seeders;

use App\Models\Experience\Experience;
use App\Models\Experience\ExperienceResponsibility;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $horizam = Experience::create([
            'role' => 'Full-Stack Developer',
            'company' => 'Horizam',
            'start_date' => '2022-01-01',
            'end_date' => null,
            'is_current' => true,
            'sort_order' => 0,
        ]);

        foreach ([
            'Built and maintained multiple Laravel web applications with Livewire and Filament admin panels',
            'Implemented REST APIs, payment integrations, and third-party service integrations',
            'Optimized database queries and application performance for large-scale systems',
        ] as $i => $desc) {
            ExperienceResponsibility::create([
                'experience_id' => $horizam->id,
                'description' => $desc,
                'sort_order' => $i,
            ]);
        }

        $softenica = Experience::create([
            'role' => 'Software Developer',
            'company' => 'Softenica',
            'start_date' => '2021-01-01',
            'end_date' => '2022-01-01',
            'is_current' => false,
            'sort_order' => 1,
        ]);

        foreach ([
            'Developed web applications using Laravel and Vue.js',
            'Collaborated with cross-functional teams to deliver client projects on schedule',
            'Participated in code reviews and implemented best practices for code quality',
        ] as $i => $desc) {
            ExperienceResponsibility::create([
                'experience_id' => $softenica->id,
                'description' => $desc,
                'sort_order' => $i,
            ]);
        }
    }
}
