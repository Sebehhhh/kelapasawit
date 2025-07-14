<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'details.product', 'payment']);

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter status
        $selectedStatus = $request->status;
        if ($selectedStatus) {
            $query->where('status', $selectedStatus);
        }

        // Filter nama user
        $searchUser = $request->user_name;
        if ($searchUser) {
            $query->whereHas('user', function($q) use ($searchUser) {
                $q->where('name', 'like', '%' . $searchUser . '%');
            });
        }

        $orders = $query->orderByDesc('created_at')->paginate(10)->appends($request->all());
        return view('admin.orders.index', compact('orders', 'selectedStatus', 'searchUser'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled',
        ]);
        $order->status = $validated['status'];
        $order->save();
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Status order berhasil diupdate.', 'order' => $order]);
        }
        return redirect()->route('admin.orders.index')->with('success', 'Status order berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Cetak laporan order dalam format PDF
     */
    public function printReport(Request $request)
    {
        $query = Order::with(['user', 'details.product', 'payment'])
            ->orderByDesc('created_at');

        // Filter berdasarkan tanggal jika ada
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Jika tidak ada filter status, kecualikan cancelled
            $query->where('status', '!=', 'cancelled');
        }

        $orders = $query->get();
        
        // Jika tidak ada data, berikan pesan yang jelas
        if ($orders->isEmpty()) {
            $message = "Tidak ada data order";
            if ($request->filled('start_date') || $request->filled('end_date')) {
                $message .= " untuk periode yang dipilih";
            }
            if ($request->filled('status')) {
                $message .= " dengan status " . ucfirst($request->status);
            }
            $message .= ".";
            
            // Redirect kembali dengan pesan error
            return redirect()->route('admin.orders.index')
                ->with('error', $message);
        }

        // Hitung statistik
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_amount'); // Sudah otomatis hanya yang bukan cancelled
        $pendingOrders = $orders->where('status', 'pending')->count();
        $paidOrders = $orders->where('status', 'paid')->count();
        $shippedOrders = $orders->where('status', 'shipped')->count();
        $cancelledOrders = $orders->where('status', 'cancelled')->count();

        $data = [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'pendingOrders' => $pendingOrders,
            'paidOrders' => $paidOrders,
            'shippedOrders' => $shippedOrders,
            'cancelledOrders' => $cancelledOrders,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
            'status' => $request->status,
        ];

        $pdf = Pdf::loadView('admin.orders.report-pdf', $data);
        
        return $pdf->download('laporan-order-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Tampilkan daftar pembayaran pending untuk validasi admin
     */
    public function paymentsValidation()
    {
        $payments = Payment::with(['order.user'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('admin.orders.payments-validation', compact('payments'));
    }

    /**
     * Validasi pembayaran: terima/tolak
     */
    public function validatePayment(Request $request, $id)
    {
        $payment = Payment::with('order')->findOrFail($id);
        $action = $request->input('action'); // 'accept' atau 'reject'
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran sudah divalidasi.');
        }
        if ($action === 'accept') {
            $payment->status = 'confirmed';
            $payment->save();
            // Update order
            $payment->order->status = 'paid';
            $payment->order->save();
            return back()->with('success', 'Pembayaran dikonfirmasi & order diupdate.');
        } elseif ($action === 'reject') {
            $payment->status = 'rejected';
            $payment->save();
            // Order tetap pending
            return back()->with('success', 'Pembayaran ditolak.');
        }
        return back()->with('error', 'Aksi tidak valid.');
    }
}
