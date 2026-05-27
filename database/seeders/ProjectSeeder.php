<?php

namespace Database\Seeders;

use App\Models\Project\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'title' => 'Autotheory',
            'slug' => 'autotheory',
            'short_description' => 'A comprehensive automotive theory learning platform with interactive quizzes, progress tracking, and adaptive learning paths.',
            'description' => 'Built to help users prepare for driving theory exams efficiently. Features include mock tests, progress analytics, and spaced repetition.',
            'tech_stack' => ['Laravel', 'Livewire', 'Tailwind CSS', 'MySQL'],
            'is_featured' => true,
            'sort_order' => 0,
            'completed_at' => '2024-06-15',
        ]);

        Project::create([
            'title' => 'Workforce & Job Management System',
            'slug' => 'workforce-job-management',
            'short_description' => 'An enterprise workforce management platform for scheduling, job tracking, employee management, and reporting.',
            'description' => 'Features real-time dashboards, automated notifications, role-based access control, and comprehensive reporting.',
            'tech_stack' => ['Laravel', 'Filament', 'Alpine.js', 'REST API'],
            'is_featured' => true,
            'sort_order' => 1,
            'completed_at' => '2024-01-20',
        ]);

        Project::create([
            'title' => 'E-Commerce Analytics Dashboard',
            'slug' => 'ecommerce-analytics',
            'short_description' => 'Real-time analytics dashboard for e-commerce businesses with sales tracking, inventory management, and customer insights.',
            'description' => 'A data-driven dashboard providing actionable insights for online stores.',
            'tech_stack' => ['Laravel', 'Livewire', 'Chart.js', 'PostgreSQL', 'Redis'],
            'is_featured' => false,
            'sort_order' => 2,
            'completed_at' => '2023-08-10',
        ]);
    }
}
