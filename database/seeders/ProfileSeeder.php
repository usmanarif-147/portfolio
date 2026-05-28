<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'usmanarif.9219@gmail.com')->firstOrFail();

        // profile_image is intentionally null — re-uploaded via the admin UI after migrate-fresh.
        Profile::create([
            'user_id' => $user->id,
            'tagline' => 'Full-Stack Developer',
            'bio' => 'Software Engineer with 5+ years of experience building scalable Laravel applications and REST APIs. Skilled in full stack development using Laravel, Vue.js, and Livewire, with experience in real-time systems, microservices, payment integrations, automated testing, and cloud deployments. Worked on SaaS, multi-vendor, enterprise, and real-time communication platforms.',
            'profile_image' => null,
            'secondary_email' => 'usman@example.com',
            'phone' => '03364238599',
            'location' => 'Lahore, Pakistan',
            'linkedin_url' => 'https://www.linkedin.com/in/us-tech-nerd',
            'github_url' => 'https://github.com/usmanarif-147',
            'availability_status' => 'Open to opportunities',
            'timezone' => 'UTC',
            'language' => 'en',
        ]);
    }
}
