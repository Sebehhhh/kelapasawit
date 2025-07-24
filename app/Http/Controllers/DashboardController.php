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
        $avgRating = Testimonial::avg('rating') ?: 0;
        $totalCustomer = User::where('role', 'customer')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalOwner = User::where('role', 'owner')->count();
        $orderPending = Order::where('status', 'pending')->count();
        $orderSelesai = Order::where('status', 'shipped')->count();

        // Produk terlaris (top 5) - exclude cancelled orders
        $produkTerlaris = Product::withCount(['orderItems as total_terjual' => function($q) {
            $q->select(DB::raw('SUM(quantity)'))
              ->whereHas('order', function($subQ) {
                  $subQ->where('status', '!=', 'cancelled');
              });
        }])->orderByDesc('total_terjual')->take(5)->get();

        // Order terbaru (5 terakhir)
        $orderTerbaru = Order::with('user')->orderByDesc('created_at')->take(5)->get();
        
        // Promosi aktif dengan diskon
        $promosiAktif = Promotion::with('product')
            ->active()
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
        
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

        // --- Tambahan statistik bisnis modern ---
        // Omset (Gross Sales) bulan ini
        $grossSalesMonth = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Total nominal retur bulan ini
        $totalReturnAmountMonth = \App\Models\ProductReturn::whereMonth('return_date', $month)
            ->whereYear('return_date', $year)
            ->with('product')
            ->get()
            ->sum(function($ret) {
                return ($ret->product ? $ret->product->price : 0) * $ret->quantity;
            });

        // Revenue (Net Sales) bulan ini
        $netRevenueMonth = $grossSalesMonth - $totalReturnAmountMonth;

        // Hitung keuntungan berdasarkan selisih harga jual dengan harga beli
        $totalProfitMonth = \App\Models\OrderDetail::whereHas('order', function($q) use ($month, $year) {
                $q->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year)
                  ->where('status', '!=', 'cancelled');
            })
            ->with(['product'])
            ->get()
            ->sum(function($orderDetail) {
                if (!$orderDetail->product) {
                    return 0;
                }
                
                $sellPrice = $orderDetail->price;
                $productId = $orderDetail->product->id;
                
                // Ambil harga beli rata-rata dari supplier purchase
                $avgCostPrice = \App\Models\SupplierPurchase::where('product_id', $productId)
                    ->avg('unit_price');
                
                // Jika tidak ada data pembelian supplier, anggap cost = 70% dari harga jual (margin 30%)
                if (!$avgCostPrice) {
                    $avgCostPrice = $sellPrice * 0.7;
                }
                
                $profitPerUnit = $sellPrice - $avgCostPrice;
                return max(0, $profitPerUnit * $orderDetail->quantity); // Pastikan tidak negatif
            });

        // Order success rate bulan ini
        $orderSuccessMonth = $totalOrderMonth > 0 ? round($orderSelesai / $totalOrderMonth * 100, 1) : 0;

        // Average order value bulan ini
        $avgOrderValueMonth = $totalOrderMonth > 0 ? round($grossSalesMonth / $totalOrderMonth) : 0;

        // Customer baru bulan ini
        $newCustomerMonth = User::where('role', 'customer')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        // Produk stok rendah (<= 10)
        $produkStokRendah = Product::where('stock', '<=', 10)->orderBy('stock')->take(5)->get();

        // Order retur bulan ini
        $orderReturMonth = \App\Models\ProductReturn::whereMonth('return_date', $month)
            ->whereYear('return_date', $year)
            ->count();

        // Top customer bulan ini (berdasarkan total belanja) - exclude cancelled orders
        $topCustomerMonth = User::where('role', 'customer')
            ->with(['orders' => function($q) use ($month, $year) {
                $q->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year)
                  ->where('status', '!=', 'cancelled');
            }])
            ->get()
            ->map(function($user) {
                $total = $user->orders->sum('total_amount');
                return [
                    'name' => $user->name,
                    'total' => $total
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();

        // Grafik status order bulan ini (pie/donut)
        $orderStatusLabels = ['pending', 'paid', 'shipped', 'cancelled'];
        $orderStatusData = [];
        foreach ($orderStatusLabels as $status) {
            $orderStatusData[$status] = Order::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('status', $status)
                ->count();
        }

        // Grafik pertumbuhan customer 6 bulan terakhir
        $chartCustomer = [ 'labels' => [], 'data' => [] ];
        for ($i = 5; $i >= 0; $i--) {
            $currentMonth = Carbon::now()->subMonths($i);
            $monthName = $currentMonth->translatedFormat('F');
            $chartCustomer['labels'][] = $monthName;
            $chartCustomer['data'][] = User::where('role', 'customer')
                ->whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->count();
        }

        return view('dashboard', compact(
            'totalOrderToday', 'totalOrderMonth', 'totalRevenueMonth', 'totalProduct',
            'totalPromo', 'totalTestimoni', 'avgRating', 'totalCustomer', 'totalAdmin', 'totalOwner',
            'orderPending', 'orderSelesai', 'produkTerlaris', 'orderTerbaru', 'chartPenjualan',
            'promosiAktif',
            // tambahan metrik bisnis
            'grossSalesMonth', 'netRevenueMonth', 'totalProfitMonth', 'orderSuccessMonth', 'avgOrderValueMonth',
            'newCustomerMonth', 'produkStokRendah', 'orderReturMonth', 'topCustomerMonth',
            'orderStatusLabels', 'orderStatusData', 'chartCustomer'
        ));
    }
}