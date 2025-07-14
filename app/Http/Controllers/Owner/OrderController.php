<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'details.product', 'payment']);
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        $selectedStatus = $request->status;
        if ($selectedStatus) {
            $query->where('status', $selectedStatus);
        }
        $searchUser = $request->user_name;
        if ($searchUser) {
            $query->whereHas('user', function($q) use ($searchUser) {
                $q->where('name', 'like', '%' . $searchUser . '%');
            });
        }
        $orders = $query->orderByDesc('created_at')->paginate(10)->appends($request->all());
        return view('owner.orders.index', compact('orders', 'selectedStatus', 'searchUser'));
    }
    public function printReport(Request $request)
    {
        $query = Order::with(['user', 'details.product', 'payment'])
            ->orderByDesc('created_at');
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'cancelled');
        }
        $orders = $query->get();
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_amount');
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
        $pdf = Pdf::loadView('owner.orders.report-pdf', $data);
        return $pdf->download('laporan-order-' . date('Y-m-d') . '.pdf');
    }
} 