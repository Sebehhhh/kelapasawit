<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductReturn;
use App\Models\Product;
use Carbon\Carbon;

class ProductReturnSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $orders = \App\Models\Order::all();
        
        if ($products->isEmpty() || $orders->isEmpty()) {
            return;
        }

        $returnReasons = [
            'Bibit tidak sesuai spesifikasi',
            'Kondisi bibit rusak saat pengiriman',
            'Salah varietas yang dikirim',
            'Bibit layu/mati saat diterima',
            'Kualitas bibit tidak sesuai standar'
        ];

        // Buat 8-12 retur dalam 6 bulan terakhir (realistis)
        $returnCount = rand(8, 12);
        
        for ($i = 0; $i < $returnCount; $i++) {
            $order = $orders->random();
            $product = $products->random();
            $returnDate = Carbon::now()->subDays(rand(1, 180));
            $quantity = rand(1, 10); // Quantity retur biasanya sedikit
            $reason = $returnReasons[array_rand($returnReasons)];
            
            ProductReturn::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'reason' => $reason,
                'return_date' => $returnDate,
                'status' => 'processed',
                'note' => 'Retur ' . $product->name . ' - ' . $reason
            ]);
        }
    }
}