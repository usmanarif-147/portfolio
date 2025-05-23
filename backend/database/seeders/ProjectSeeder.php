<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'title' => 'Portfolio Website',
                'slug' => Str::slug('Portfolio Website'),
                'description' => 'A personal portfolio website.',
                'project_url' => 'https://johnportfolio.com',
                'github_url' => 'https://github.com/johndoe/portfolio',
                'images' => json_encode([]),
                'tech_stack' => json_encode(['Vue.js', 'Tailwind CSS', 'Laravel']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Task Manager App',
                'slug' => Str::slug('Task Manager App'),
                'description' => 'A full-featured task management app.',
                'project_url' => 'https://taskapp.com',
                'github_url' => 'https://github.com/johndoe/taskapp',
                'images' => json_encode([]),
                'tech_stack' => json_encode(['React', 'Node.js', 'MongoDB']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
