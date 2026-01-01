<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'nama' => 'Admin Utama',
            'email' => 'admin@mail.com',
            'password' => Hash::make('12345'),
            'role' => 'admin',
        ]);

        // User biasa
        User::create([
            'nama' => 'User Biasa',
            'email' => 'user@mail.com',
            'password' => Hash::make('12345'),
            'role' => 'user',
        ]);
    }
}
