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
        $statusWeights = [
            'pending' => 15,   // 15% pending
            'paid' => 25,      // 25% paid  
            'shipped' => 50,   // 50% shipped (sukses)
            'cancelled' => 10  // 10% cancelled
        ];
        
        // Buat 50 order dengan distribusi status yang realistis dalam 6 bulan terakhir
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            
            // Pilih status berdasarkan probabilitas
            $rand = rand(1, 100);
            $cumulative = 0;
            $selectedStatus = 'pending';
            foreach ($statusWeights as $status => $weight) {
                $cumulative += $weight;
                if ($rand <= $cumulative) {
                    $selectedStatus = $status;
                    break;
                }
            }
            
            // Buat tanggal random dalam 180 hari terakhir (6 bulan)
            $orderDate = Carbon::now()->subDays(rand(1, 180));
            
            // Buat order dengan beberapa produk
            $order = Order::create([
                'user_id' => $user->id,
                'order_date' => $orderDate,
                'status' => $selectedStatus,
                'total_amount' => 0, // akan dihitung setelah detail
            ]);
            
            // Tambahkan 1-4 produk per order
            $productCount = rand(1, 4);
            $orderTotal = 0;
            
            for ($j = 0; $j < $productCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 20); // Quantity realistis
                $sellPrice = $product->price; // Gunakan harga produk sebenarnya
                $subtotal = $quantity * $sellPrice;
                $orderTotal += $subtotal;
                
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $sellPrice,
                ]);
            }
            
            // Update total order
            $order->update(['total_amount' => $orderTotal]);
        }
    }
} 