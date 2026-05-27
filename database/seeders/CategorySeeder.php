<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Categories (used to group Skills in the Skills & Tech section)
        foreach ([
            ['name' => 'Frontend', 'sort_order' => 0],
            ['name' => 'Backend', 'sort_order' => 1],
            ['name' => 'Database & Tools', 'sort_order' => 2],
        ] as $cat) {
            Category::create($cat + ['slug' => Str::slug($cat['name'])]);
        }
    }
}
