<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupplierPurchase;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;

class SupplierPurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $categories = Category::all();

        // Data supplier yang realistis
        $suppliers = [
            'PT Kelapa Sawit Unggul',
            'CV Bibit Sawit Nusantara', 
            'Koperasi Petani Sawit Jaya',
            'PT Agro Sawit Sejahtera',
            'UD Sawit Berkah'
        ];

        foreach ($products as $product) {
            // Buat beberapa pembelian untuk setiap produk dalam 6 bulan terakhir
            $purchaseCount = rand(3, 8);
            
            for ($i = 0; $i < $purchaseCount; $i++) {
                $purchaseDate = Carbon::now()->subDays(rand(1, 180));
                
                // Tentukan harga beli yang realistis (70-85% dari harga jual)
                $costPercentage = rand(70, 85) / 100;
                $unitPrice = round($product->price * $costPercentage, -2); // Bulatkan ke ratusan
                
                $quantity = rand(10, 100);
                $totalPrice = $unitPrice * $quantity;
                
                SupplierPurchase::create([
                    'supplier_name' => $suppliers[array_rand($suppliers)],
                    'category_id' => $product->category_id,
                    'product_id' => $product->id,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                    'purchase_date' => $purchaseDate,
                    'note' => 'Pembelian ' . $product->name . ' batch ' . ($i + 1)
                ]);
            }
        }
    }
}