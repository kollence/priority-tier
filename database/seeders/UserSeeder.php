<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Create admin user
                User::create([
                    'name' => 'Admin User',
                    'email' => 'admin@mail.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);
        
                // Create manager user
                User::create([
                    'name' => 'Manager User',
                    'email' => 'manager@mail.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);
        
                // Create regular user
                User::create([
                    'name' => 'Regular User',
                    'email' => 'user@mail.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);
        
                // Create additional random users
                User::factory(5)->create();
    }
}
