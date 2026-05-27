<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Usman Arif',
            'email' => 'usmanarif.9219@gmail.com',
            'password' => '11223344',
        ]);
    }
}
