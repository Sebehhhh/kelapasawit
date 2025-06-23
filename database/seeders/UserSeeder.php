<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin dummy
        User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@kelapasawit.com',
            'password' => Hash::make('password'), // Jangan lupa ganti saat production!
            'phone' => '081234567890',
            'role' => 'admin',
        ]);

        // Customer dummy
        User::create([
            'name' => 'Customer Demo',
            'email' => 'customer@kelapasawit.com',
            'password' => Hash::make('password'),
            'phone' => '081298765432',
            'role' => 'pelanggan',
        ]);
    }
}
