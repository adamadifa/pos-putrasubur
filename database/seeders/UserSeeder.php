<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing users with role if they don't have one
        User::whereNull('role')->update(['role' => 'admin']);

        // Create default admin user if not exists
        if (!User::where('email', 'admin@pos.com')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@pos.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        // Create default manager user if not exists
        if (!User::where('email', 'manager@pos.com')->exists()) {
            User::create([
                'name' => 'Manager',
                'email' => 'manager@pos.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'email_verified_at' => now(),
            ]);
        }

        // Create default kasir user if not exists
        if (!User::where('email', 'kasir@pos.com')->exists()) {
            User::create([
                'name' => 'Kasir',
                'email' => 'kasir@pos.com',
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'email_verified_at' => now(),
            ]);
        }
    }
}
