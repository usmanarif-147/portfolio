<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Skill\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        // name => id map, built from the categories created by CategorySeeder
        $categoryMap = Category::pluck('id', 'name');

        // Skills (technical — grouped by Category in the Skills & Tech section)
        $skills = [
            ['title' => 'HTML5', 'category' => 'Frontend', 'sort_order' => 0],
            ['title' => 'CSS3', 'category' => 'Frontend', 'sort_order' => 1],
            ['title' => 'JavaScript', 'category' => 'Frontend', 'sort_order' => 2],
            ['title' => 'Alpine.js', 'category' => 'Frontend', 'sort_order' => 3],
            ['title' => 'Livewire', 'category' => 'Frontend', 'sort_order' => 4],
            ['title' => 'Tailwind CSS', 'category' => 'Frontend', 'sort_order' => 5],
            ['title' => 'Bootstrap', 'category' => 'Frontend', 'sort_order' => 6],
            ['title' => 'jQuery', 'category' => 'Frontend', 'sort_order' => 7],
            ['title' => 'PHP', 'category' => 'Backend', 'sort_order' => 0],
            ['title' => 'Laravel', 'category' => 'Backend', 'sort_order' => 1],
            ['title' => 'Filament', 'category' => 'Backend', 'sort_order' => 2],
            ['title' => 'REST APIs', 'category' => 'Backend', 'sort_order' => 3],
            ['title' => 'Python', 'category' => 'Backend', 'sort_order' => 4],
            ['title' => 'Node.js', 'category' => 'Backend', 'sort_order' => 5],
            ['title' => 'MySQL', 'category' => 'Database & Tools', 'sort_order' => 0],
            ['title' => 'PostgreSQL', 'category' => 'Database & Tools', 'sort_order' => 1],
            ['title' => 'Redis', 'category' => 'Database & Tools', 'sort_order' => 2],
            ['title' => 'Git', 'category' => 'Database & Tools', 'sort_order' => 3],
            ['title' => 'Docker', 'category' => 'Database & Tools', 'sort_order' => 4],
            ['title' => 'Linux', 'category' => 'Database & Tools', 'sort_order' => 5],
            ['title' => 'AWS', 'category' => 'Database & Tools', 'sort_order' => 6],
            ['title' => 'CI/CD', 'category' => 'Database & Tools', 'sort_order' => 7],
        ];

        foreach ($skills as $skill) {
            Skill::create([
                'title' => $skill['title'],
                'category_id' => $categoryMap[$skill['category']],
                'sort_order' => $skill['sort_order'],
            ]);
        }
    }
}
