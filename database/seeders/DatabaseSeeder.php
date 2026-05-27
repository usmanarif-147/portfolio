<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Portfolio data (order matters: ProfileSeeder needs the user,
            // SkillSeeder needs the categories).
            UserSeeder::class,
            ProfileSeeder::class,
            // CategorySeeder::class,
            // StrengthSeeder::class,
            // SkillSeeder::class,
            // ExperienceSeeder::class,
            // EducationSeeder::class,
            // ProjectSeeder::class,
            // BlogPostSeeder::class,

            // // Default lookup data for other modules.
            // BookmarkCategorySeeder::class,
            // ExpenseCategorySeeder::class,
        ]);
    }
}
