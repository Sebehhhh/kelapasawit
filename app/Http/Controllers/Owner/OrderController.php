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

    public function printSalesReport(Request $request)
    {
        $orders = Order::where('status', 'shipped')
            ->selectRaw('DATE(created_at) as tanggal, SUM(total_amount) as total, COUNT(*) as jumlah')
            ->groupByRaw('DATE(created_at)')
            ->orderByDesc('tanggal')
            ->get();
        $pdf = Pdf::loadView('owner.orders.sales-report-pdf', compact('orders'));
        return $pdf->download('laporan-penjualan-harian-' . date('Y-m-d') . '.pdf');
    }

    public function printStrukKeluar(Request $request)
    {
        $orders = Order::with(['user', 'details.product'])
            ->where('status', 'shipped')
            ->orderByDesc('created_at')->get();
        $pdf = Pdf::loadView('owner.orders.struk-keluar-pdf', compact('orders'));
        return $pdf->download('struk-keluar-penjualan-' . date('Y-m-d') . '.pdf');
    }

    public function printStrukMasuk(Request $request)
    {
        $invoices = \App\Models\PurchaseInvoice::with(['details.product'])->orderByDesc('purchase_date')->get();
        $pdf = Pdf::loadView('owner.orders.struk-masuk-pdf', compact('invoices'));
        return $pdf->download('struk-masuk-pembelian-' . date('Y-m-d') . '.pdf');
    }
} 