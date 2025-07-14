<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Promotion;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        // Statistik utama
        $totalOrderToday = Order::whereDate('created_at', $today)->count();
        $totalOrderMonth = Order::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
        $totalRevenueMonth = Order::whereMonth('created_at', $month)->whereYear('created_at', $year)->where('status', '!=', 'cancelled')->sum('total_amount');
        $totalProduct = Product::count();
        $totalPromo = Promotion::count();
        $totalTestimoni = Testimonial::count();
        $totalCustomer = User::where('role', 'customer')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalOwner = User::where('role', 'owner')->count();
        $orderPending = Order::where('status', 'pending')->count();
        $orderSelesai = Order::where('status', 'shipped')->count();

        // Produk terlaris (top 5)
        $produkTerlaris = Product::withCount(['orderItems as total_terjual' => function($q) {
            $q->select(DB::raw('SUM(quantity)'));
        }])->orderByDesc('total_terjual')->take(5)->get();

        // Order terbaru (5 terakhir)
        $orderTerbaru = Order::with('user')->orderByDesc('created_at')->take(5)->get();
        
        // Data untuk grafik penjualan bulanan
        $chartPenjualan = [];
        $chartPenjualan['labels'] = [];
        $chartPenjualan['data'] = [];
        
        // Mengambil data penjualan 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $currentMonth = Carbon::now()->subMonths($i);
            $monthName = $currentMonth->translatedFormat('F');
            $monthYear = $currentMonth->format('Y-m');
            
            $chartPenjualan['labels'][] = $monthName;
            
            $monthlyRevenue = Order::whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');
                
            $chartPenjualan['data'][] = (int) $monthlyRevenue;
        }

        return view('dashboard', compact(
            'totalOrderToday', 'totalOrderMonth', 'totalRevenueMonth', 'totalProduct',
            'totalPromo', 'totalTestimoni', 'totalCustomer', 'totalAdmin', 'totalOwner',
            'orderPending', 'orderSelesai', 'produkTerlaris', 'orderTerbaru', 'chartPenjualan'
        ));
    }
}