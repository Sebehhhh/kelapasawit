<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user dan product
        $users = User::where('role', 'customer')->get();
        $products = Product::all();
        
        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'paid', 'shipped', 'cancelled'];
        
        // Buat 20 order dengan status berbeda
        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $product = $products->random();
            $status = $statuses[array_rand($statuses)];
            $quantity = rand(1, 50);
            $price = rand(10000, 100000);
            $total = $quantity * $price;
            
            // Buat tanggal random dalam 30 hari terakhir
            $orderDate = Carbon::now()->subDays(rand(0, 30));
            
            $order = Order::create([
                'user_id' => $user->id,
                'order_date' => $orderDate,
                'status' => $status,
                'total_amount' => $total,
            ]);
            
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }
    }
} 