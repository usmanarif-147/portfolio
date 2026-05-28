<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Run order matters:
     *  - ProfileSeeder needs the user from UserSeeder.
     *  - SkillSeeder needs the categories from CategorySeeder.
     *  - The rest have no cross-table FK dependencies.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProfileSeeder::class,
            CategorySeeder::class,
            SkillSeeder::class,
            EducationSeeder::class,
            ExperienceSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
