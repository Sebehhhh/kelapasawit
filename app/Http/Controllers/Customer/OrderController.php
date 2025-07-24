<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['details.product', 'details.testimonial', 'details.product.category', 'payment'])
            ->where('user_id', auth()->id())
            ->orderByDesc('order_date')
            ->paginate(10);
        // Ambil order detail yang eligible untuk testimoni
        $eligibleOrderDetails = \App\Models\OrderDetail::whereHas('order', function($q) {
            $q->where('user_id', auth()->id())->where('status', 'shipped');
        })
        ->whereDoesntHave('testimonial')
        ->with('product')
        ->get();
        return view('customer.orders.index', compact('orders', 'eligibleOrderDetails'));
    }

    /**
     * Store new order from checkout modal (AJAX).
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        // DB Transaction: aman dari error stok
        DB::beginTransaction();
        try {
            $product = Product::lockForUpdate()->findOrFail($request->product_id);

            // Validasi stok
            if ($request->qty > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak cukup! Sisa stok: ' . $product->stock,
                ]);
            }

            // Check for active promotion
            $promotion = \App\Models\Promotion::active()
                ->forProduct($product->id)
                ->first();

            $originalPrice = $product->price;
            $finalPrice = $originalPrice;
            $discountAmount = 0;
            $promotionId = null;

            if ($promotion) {
                $discountAmount = $promotion->calculateDiscount($originalPrice, $request->qty);
                $finalPrice = $promotion->getFinalPrice($originalPrice, $request->qty); // per unit after discount
                $promotionId = $promotion->id;
            }

            $total = $finalPrice * $request->qty;

            // Simpan Order
            $order = Order::create([
                'user_id'      => $user->id,
                'order_date'   => now(),
                'status'       => 'pending',
                'total_amount' => $total,
            ]);

            // Simpan OrderDetail
            OrderDetail::create([
                'order_id'        => $order->id,
                'product_id'      => $product->id,
                'quantity'        => $request->qty,
                'price'           => $finalPrice,
                'promotion_id'    => $promotionId,
                'original_price'  => $originalPrice,
                'discount_amount' => $discountAmount,
            ]);

            // Stok akan otomatis dikurangi oleh OrderDetail model event, jadi tidak perlu manual decrement di sini

            DB::commit();

            $message = 'Order berhasil dibuat! Silakan lakukan pembayaran.';
            if ($promotion && $discountAmount > 0) {
                $message .= ' Anda mendapat diskon Rp ' . number_format($discountAmount, 0, ',', '.') . ' dari promosi "' . $promotion->title . '"!';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'order_id' => $order->id,
                'discount_applied' => $discountAmount > 0,
                'discount_amount' => $discountAmount,
                'original_total' => $originalPrice * $request->qty,
                'final_total' => $total
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi error: ' . $e->getMessage(),
            ]);
        }
    }
}
