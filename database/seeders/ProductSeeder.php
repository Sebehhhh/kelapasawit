<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [
                'category_id' => 1,
                'name' => 'Bibit Sawit Unggul A',
                'description' => 'Bibit sawit varietas unggul, siap tanam.',
                'price' => 30000,
                'stock' => 200,
                // 'unit' => 'polybag',
                'image' => 'produk1.jpg',
                // 'status' => 'aktif',
            ],
            [
                'category_id' => 2,
                'name' => 'Bibit Sawit Lokal B',
                'description' => 'Bibit sawit lokal, sehat dan berkualitas.',
                'price' => 25000,
                'stock' => 150,
                // 'unit' => 'polybag',
                'image' => 'produk2.jpg',
                // 'status' => 'aktif',
            ],
            [
                'category_id' => 3,
                'name' => 'Bibit Sawit Impor C',
                'description' => 'Bibit impor dari Thailand, produktivitas tinggi.',
                'price' => 40000,
                'stock' => 100,
                // 'unit' => 'polybag',
                'image' => 'produk3.jpg',
                // 'status' => 'aktif',
            ],
        ]);
    }
}
