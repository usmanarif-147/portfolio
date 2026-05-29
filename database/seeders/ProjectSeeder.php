<?php

namespace Database\Seeders;

use App\Models\Project\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Featured portfolio projects. cover_image is intentionally null on every row —
     * the owner re-uploads project images via the admin UI after migrate-fresh.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'RehabSuite — Multi-Tenant Physiotherapy SaaS Platform',
                'short_description' => 'Built a real-time communication system with WebSocket chat and WebRTC voice/video calling using Laravel, Rust/Axum, Kafka, and LiveKit.',
                'tech_stack' => ['Laravel 12', 'Rust/Axum', 'Kafka', 'PostgreSQL', 'Docker', 'Playwright'],
                'demo_url' => 'https://facility.rehasaku.net/en-US',
                'is_featured' => true,
            ],
            [
                'title' => 'Autotheory — Multi-Vendor Auto Services Platform',
                'short_description' => 'Developed a multi-role platform with vendor onboarding, booking management, Stripe Connect split payments, Redis caching, and FCM notifications.',
                'tech_stack' => ['Laravel', 'Vue.js', 'Livewire', 'Redis', 'Stripe Connect', 'MySQL'],
                'demo_url' => 'https://autotheory.com/',
                'is_featured' => true,
            ],
            [
                'title' => 'Workforce & Job Management System',
                'short_description' => 'Built role-based job and employee management modules with real-time dashboards, performance tracking, and notification systems.',
                'tech_stack' => ['Laravel 10', 'Livewire', 'Filament', 'Alpine.js', 'Bootstrap', 'MySQL'],
                'demo_url' => 'https://thislinks.co.uk/',
                'is_featured' => true,
            ],
            [
                'title' => 'GoTap — NFC Smart Business-Card & Digital-Profile Platform',
                'short_description' => 'A digital networking platform where people share their contact details with a tap. Users build rich digital profiles, link them to physical **NFC cards** and **QR codes**, capture lead',
                'tech_stack' => ['Laravel 9', 'Livewire 2', 'MySQL 8', 'Alpine.js', 'Bootstrap'],
                'demo_url' => 'https://gotaps.me/',
                'is_featured' => true,
            ],
        ];

        foreach ($projects as $data) {
            Project::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'short_description' => $data['short_description'],
                'description' => null,
                'cover_image' => null,
                'tech_stack' => $data['tech_stack'],
                'demo_url' => $data['demo_url'],
                'github_url' => null,
                'is_featured' => $data['is_featured'],
                'sort_order' => 0,
                'is_active' => true,
                'completed_at' => null,
            ]);
        }
    }
}
