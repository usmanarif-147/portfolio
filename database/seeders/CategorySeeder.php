<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Skill categories shown on the public portfolio's "Skills & Technologies"
     * section. Order here matches the order owner wants them rendered.
     *
     * Slug is set explicitly — Category::saving() would normally auto-derive
     * it, but DatabaseSeeder uses WithoutModelEvents, which mutes that hook
     * during seeding.
     */
    public function run(): void
    {
        foreach ([
            'Backend',
            'Frontend',
            'Real-Time',
            'Databases',
            'Architecture',
            'DevOps',
            'Testing & Tools',
        ] as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'sort_order' => 0,
            ]);
        }
    }
}
