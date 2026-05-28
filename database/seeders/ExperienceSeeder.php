<?php

namespace Database\Seeders;

use App\Models\Experience\Experience;
use App\Models\Experience\ExperienceResponsibility;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Work history shown on the public timeline and (when is_for_resume=true)
     * in the Resume Builder. All three experiences are currently flagged for the
     * resume; the owner can flip them off via the admin toggle.
     */
    public function run(): void
    {
        $experiences = [
            [
                'role' => 'Software Engineer (Full Stack)',
                'company' => 'JSYS Tech',
                'start_date' => '2025-08-28',
                'end_date' => null,
                'is_current' => false,
                'responsibilities' => [
                    'Working on a multi-tenant physiotherapy SaaS platform with real-time chat and WebRTC calling features.',
                    'Developed JWT-authenticated WebSocket chat systems with message reactions, typing indicators, read receipts, and presence tracking.',
                    'Integrated LiveKit for voice/video calling, call recording, and AI-based transcription workflows.',
                    'Built backend services using Laravel and Rust/Axum within a Kafka-driven microservices architecture.',
                    'Wrote Playwright end-to-end tests and contributed to Jenkins CI/CD workflows.',
                ],
            ],
            [
                'role' => 'Software Engineer (Full Stack)',
                'company' => 'Horizam',
                'start_date' => '2022-04-28',
                'end_date' => '2025-07-28',
                'is_current' => false,
                'responsibilities' => [
                    'Developed scalable Laravel applications and REST APIs for web and mobile platforms.',
                    'Built multi-vendor systems with vendor onboarding, booking management, Stripe Connect payments, and FCM notifications.',
                    'Designed database schemas, implemented Redis caching, and optimized complex SQL queries.',
                    'Migrated legacy CodeIgniter applications to Laravel with complete database redesign.',
                    'Introduced automated testing using Laravel Pest and improved code reliability across projects.',
                ],
            ],
            [
                'role' => 'Laravel Developer',
                'company' => 'Softenica',
                'start_date' => '2021-03-28',
                'end_date' => '2022-07-28',
                'is_current' => false,
                'responsibilities' => [
                    'Developed backend services and REST APIs for client projects using Laravel.',
                    'Worked on admin panels, authentication systems, and CRUD-based business modules.',
                    'Assisted in database optimization and integration of third-party APIs.',
                    'Collaborated with frontend developers and participated in agile sprint workflows.',
                ],
            ],
        ];

        foreach ($experiences as $data) {
            $experience = Experience::create([
                'role' => $data['role'],
                'company' => $data['company'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_current' => $data['is_current'],
                'description' => '',
                'sort_order' => 0,
                'is_active' => true,
                'is_for_resume' => true,
            ]);

            foreach ($data['responsibilities'] as $i => $bullet) {
                ExperienceResponsibility::create([
                    'experience_id' => $experience->id,
                    'description' => $bullet,
                    'sort_order' => $i,
                ]);
            }
        }
    }
}
