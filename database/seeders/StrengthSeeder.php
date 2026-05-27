<?php

namespace Database\Seeders;

use App\Models\Strength;
use Illuminate\Database\Seeder;

class StrengthSeeder extends Seeder
{
    public function run(): void
    {
        // Strengths (soft attributes shown in About Me).
        // Note: welcome.blade.php wraps {{ $skill->icon }} inside <path d="..."/>,
        // so the icon column must hold just the SVG path data (the "M..." string).
        $strengths = [
            ['title' => 'Problem Solving', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'sort_order' => 0],
            ['title' => 'Creativity', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', 'sort_order' => 1],
            ['title' => 'Adaptability', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'sort_order' => 2],
            ['title' => 'Optimization', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'sort_order' => 3],
        ];

        foreach ($strengths as $strength) {
            Strength::create($strength);
        }
    }
}
