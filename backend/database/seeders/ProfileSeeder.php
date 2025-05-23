<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profiles')->insert([
            'full_name' => 'John Doe',
            'title' => 'Full Stack Developer',
            'bio' => 'Passionate developer with 5+ years of experience.',
            'email' => 'john@example.com',
            'phone' => '123-456-7890',
            'location' => 'New York, USA',
            'profile_image' => null,
            'linkedin' => 'https://linkedin.com/in/johndoe',
            'github' => 'https://github.com/johndoe',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
