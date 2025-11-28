<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@nps.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_internal' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create an Editor
        User::updateOrCreate(
            ['email' => 'editor@nps.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'is_internal' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create a regular Internal User
        User::updateOrCreate(
            ['email' => 'internal@nps.com'],
            [
                'name' => 'Internal User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_internal' => true,
                'email_verified_at' => now(),
            ]
        );
        
        // Create a Public User
        User::updateOrCreate(
            ['email' => 'user@nps.com'],
            [
                'name' => 'Public User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_internal' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
