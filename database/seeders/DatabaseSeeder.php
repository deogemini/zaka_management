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
        // Create Admin User
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@limitless.com',
                'phone' => '0700000000',
                'password' => bcrypt('password'), // You can change this
                'role' => 'admin',
            ]);
        }

        // Create Regular Test User if not exists
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'user',
            ]);
        }
    }
}
