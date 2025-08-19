<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'email_verified_at' => now(),
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'abi@gmail.com'],
            [
                'name' => 'Mas Ndronk',
                'password' => Hash::make('123456'),
                'phone' => '1234567890',
                'email_verified_at' => now(),
                'role' => 'user'
            ]
        );
    }
}
