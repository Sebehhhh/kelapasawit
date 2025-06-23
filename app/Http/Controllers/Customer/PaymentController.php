<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Store a newly created payment in storage (via AJAX/modal).
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id'       => 'required|exists:orders,id',
            'amount_paid'    => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
            'proof_image'    => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $order = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($request->order_id);

        // Cek jika sudah ada payment untuk order ini
        if ($order->payment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran untuk pesanan ini sudah di-upload.',
            ]);
        }

        // Upload bukti pembayaran
        $path = $request->file('proof_image')->store('payments', 'public');

        // Simpan data payment
        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'order_id'       => $order->id,
                'payment_date'   => now(),
                'amount_paid'    => $request->amount_paid,
                'payment_method' => $request->payment_method,
                'proof_image'    => $path,
                'status'         => 'pending', // diverifikasi admin nanti
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil di-upload. Silakan tunggu verifikasi dari admin.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            // Hapus file jika gagal
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload pembayaran: ' . $e->getMessage(),
            ]);
        }
    }
}