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
        $products = [
            [
                'category_id' => 1,
                'name' => 'Bibit Sawit Unggul DxP Marihat',
                'description' => 'Bibit sawit varietas unggul DxP dari Marihat, produktivitas tinggi 20-25 ton TBS/ha/tahun.',
                'price' => 35000,
                'stock' => 500,
                'image' => 'bibit-unggul-marihat.jpg',
            ],
            [
                'category_id' => 1,
                'name' => 'Bibit Sawit Unggul Simalungun',
                'description' => 'Bibit sawit unggul Simalungun, tahan hama dan penyakit, cocok untuk dataran rendah.',
                'price' => 32000,
                'stock' => 350,
                'image' => 'bibit-unggul-simalungun.jpg',
            ],
            [
                'category_id' => 1,
                'name' => 'Bibit Sawit Unggul PPKS Medan',
                'description' => 'Bibit sawit unggul dari PPKS Medan, kadar minyak tinggi 22-24%.',
                'price' => 38000,
                'stock' => 280,
                'image' => 'bibit-unggul-ppks.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'Bibit Sawit Lokal Deli',
                'description' => 'Bibit sawit lokal varietas Deli, adaptasi baik di iklim Indonesia.',
                'price' => 25000,
                'stock' => 450,
                'image' => 'bibit-lokal-deli.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'Bibit Sawit Lokal Dura',
                'description' => 'Bibit sawit lokal Dura, cangkang tebal, hasil minyak stabil.',
                'price' => 22000,
                'stock' => 380,
                'image' => 'bibit-lokal-dura.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'Bibit Sawit Lokal Pisifera',
                'description' => 'Bibit sawit lokal Pisifera, untuk pemuliaan dan pengembangan.',
                'price' => 27000,
                'stock' => 200,
                'image' => 'bibit-lokal-pisifera.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'Bibit Sawit Impor Malaysia FELDA',
                'description' => 'Bibit sawit impor Malaysia dari FELDA, produktivitas premium 25-30 ton/ha.',
                'price' => 45000,
                'stock' => 150,
                'image' => 'bibit-impor-felda.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'Bibit Sawit Impor Thailand Tenera',
                'description' => 'Bibit sawit impor Thailand varietas Tenera, pertumbuhan cepat.',
                'price' => 42000,
                'stock' => 120,
                'image' => 'bibit-impor-tenera.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'Bibit Sawit Impor Costa Rica',
                'description' => 'Bibit sawit impor Costa Rica, tahan cuaca ekstrem dan penyakit.',
                'price' => 50000,
                'stock' => 80,
                'image' => 'bibit-impor-costarica.jpg',
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
