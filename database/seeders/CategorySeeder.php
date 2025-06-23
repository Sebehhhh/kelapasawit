<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'Bibit Sawit Unggul', 'description' => 'Varian unggulan'],
            ['name' => 'Bibit Sawit Lokal', 'description' => 'Bibit lokal'],
            ['name' => 'Bibit Sawit Impor', 'description' => 'Bibit impor'],
        ]);
    }
}
