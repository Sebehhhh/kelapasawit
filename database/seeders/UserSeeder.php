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
            'name' => 'Admin',
            'email' => 'admin@kelapasawit.com',
            'password' => Hash::make('password'),
            'phone' => '081234567899',
            'role' => 'admin',
        ]);
        
    }
}
