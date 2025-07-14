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
        // Owner 1
        \App\Models\User::create([
            'name' => 'Pemilik Kebun',
            'email' => 'owner@kelapasawit.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'role' => 'owner',
        ]);
        // Customer 10
        for ($i = 1; $i <= 10; $i++) {
            \App\Models\User::create([
                'name' => 'Customer ' . $i,
                'email' => 'customer' . $i . '@kelapasawit.com',
                'password' => Hash::make('password'),
                'phone' => '08120000000' . $i,
                'role' => 'customer',
            ]);
        }
    }
}
