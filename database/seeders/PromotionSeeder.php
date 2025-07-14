<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promotion::insert([
            [
                'title' => 'Promo Awal Tahun',
                'description' => 'Diskon 10% untuk semua bibit sawit unggul!',
                'start_date' => '2025-01-01',
                'end_date' => '2025-01-31',
                'product_id' => 1,
                'image' => 'promo1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Promo Bibit Lokal',
                'description' => 'Gratis ongkir untuk pembelian bibit lokal.',
                'start_date' => '2025-02-01',
                'end_date' => '2025-02-28',
                'product_id' => 2,
                'image' => 'promo2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Promo Impor',
                'description' => 'Cashback 5% untuk bibit impor.',
                'start_date' => '2025-03-01',
                'end_date' => '2025-03-31',
                'product_id' => 3,
                'image' => 'promo3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 