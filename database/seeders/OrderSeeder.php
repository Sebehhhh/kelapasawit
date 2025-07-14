<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $order = Order::create([
            'user_id' => 1,
            'order_date' => Carbon::now()->toDateString(),
            'status' => 'paid',
            'total_amount' => 300000,
        ]);
        OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => 1,
            'quantity' => 10,
            'price' => 30000,
        ]);
    }
} 