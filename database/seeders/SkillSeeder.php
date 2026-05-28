<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Skill\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        // name => id map built from the categories created by CategorySeeder.
        $categoryMap = Category::pluck('id', 'name');

        // Skills grouped by category name. Each list is in the desired display order.
        $skillsByCategory = [
            'Backend' => ['Php', 'Laravel', 'Livewire', 'REST APIs'],
            'Frontend' => ['Vue js', 'Nuxt js', 'Alpine js', 'Javascript', 'HTML/CSS', 'Bootstrap', 'Websockets'],
            'Real-Time' => ['WebRTC', 'Livekit', 'Kafka', 'FCM'],
            'Databases' => ['MySQL', 'PostgreSQL', 'Redis'],
            'Architecture' => ['Microservices', 'Event-Driven Systems'],
            'DevOps' => ['Docker', 'Linux', 'VPS Deployment', 'CI/CD', 'Jenkins'],
            'Testing & Tools' => ['Laravel Pest', 'PHPUnit', 'Playwrite', 'Git', 'Github', 'Jira', 'Postman'],
        ];

        foreach ($skillsByCategory as $categoryName => $titles) {
            foreach ($titles as $title) {
                Skill::create([
                    'title' => $title,
                    'category_id' => $categoryMap[$categoryName],
                    'sort_order' => 0,
                    'is_active' => true,
                ]);
            }
        }
    }
}
