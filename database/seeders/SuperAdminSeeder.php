<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if user exists first to avoid duplicates
        $email = 'admin@gmail.com';
        if (User::where('email', $email)->exists()) {
            return;
        }

        User::create([
            'name' => 'Super Admin',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'tenant_id' => null, // Super Admin has no tenant
        ]);
    }
}
