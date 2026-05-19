<?php

namespace Database\Seeders;

use App\Models\User;
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
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Setia Buah',
                'password' => bcrypt('admin'),
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'petani@example.com'],
            [
                'name' => 'Petani Setia Buah',
                'password' => bcrypt('petani'),
                'role' => 'petani'
            ]
        );
    }
}
