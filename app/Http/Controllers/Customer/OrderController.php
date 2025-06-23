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
        $orders = Order::with(['details.product.category'])->where('user_id', auth()->id())->orderByDesc('order_date')->paginate(10);
        return view('customer.orders.index', compact('orders'));
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

            $total = $product->price * $request->qty;

            // Simpan Order
            $order = Order::create([
                'user_id'      => $user->id,
                'order_date'   => now(),
                'status'       => 'pending',
                'total_amount' => $total,
            ]);

            // Simpan OrderDetail
            OrderDetail::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $request->qty,
                'price'      => $product->price,
            ]);

            // Kurangi stok produk
            $product->decrement('stock', $request->qty);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat! Silakan lakukan pembayaran.',
                'order_id' => $order->id
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
