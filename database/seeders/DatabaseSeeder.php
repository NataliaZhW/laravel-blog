<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserImages;
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
        User::factory()
            ->count(5)->has(UserImages::factory()->count(1), 'image')
            ->create();
    }
}
