<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Admin Farmora',
            'email' => 'admin@farmora.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'approved',
            'phone' => '9999999999',
            'location' => 'New Delhi',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);
    }
}
