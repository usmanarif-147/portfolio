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

        Profile::create([
            'user_id' => $user->id,
            'tagline' => 'Full-Stack Developer',
            'bio' => "I'm a dedicated Full-Stack Developer with 4+ years of professional experience in building robust web applications. My expertise lies in the Laravel ecosystem, where I leverage tools like Livewire, Filament, and Tailwind CSS to create seamless user experiences.\n\nI thrive on transforming complex business requirements into elegant, maintainable code. Whether it's architecting a new system from scratch or optimizing an existing application, I bring a problem-solving mindset and attention to detail.",
            'secondary_email' => 'usman@example.com',
            'location' => 'Lahore, Pakistan',
            'availability_status' => 'Open to opportunities',
        ]);
    }
}
