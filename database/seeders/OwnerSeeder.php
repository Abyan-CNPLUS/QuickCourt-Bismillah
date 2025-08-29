<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'owner@gmail.com'], // kalau sudah ada email ini, update
            [
                'name' => 'Owner QuickCourt',
                'password' => Hash::make('owner123'),
                'phone' => '08123456789',
                'email_verified_at' => now(),
                'role' => 'owner',
            ]
        );
    }
}
