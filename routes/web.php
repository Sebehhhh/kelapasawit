<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminPaymentMethodController;

use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\TestimonialController as CustomerTestimonialController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SupplierPurchaseController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'customer') {
        // Data untuk dashboard customer
        $totalOrder = \App\Models\Order::where('user_id', $user->id)->count();
        $orderPending = \App\Models\Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $orderSelesai = \App\Models\Order::where('user_id', $user->id)->where('status', 'shipped')->count();
        $totalTestimoni = \App\Models\Testimonial::where('user_id', $user->id)->count();
        $latestProducts = \App\Models\Product::orderByDesc('created_at')->take(6)->get();
        
        // Additional data for enhanced dashboard
        $totalSpent = \App\Models\Order::where('user_id', $user->id)->where('status', '!=', 'cancelled')->sum('total_amount');
        $avgCustomerRating = \App\Models\Testimonial::where('user_id', $user->id)->avg('rating') ?: 5.0;
        $orderThisMonth = \App\Models\Order::where('user_id', $user->id)->whereMonth('created_at', now()->month)->count();
        
        // Recent orders (last 5)
        $recentOrders = \App\Models\Order::where('user_id', $user->id)
            ->with('details')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
        
        // Customer order chart data (6 months)
        $customerOrderChart = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $currentMonth = \Carbon\Carbon::now()->subMonths($i);
            $monthName = $currentMonth->translatedFormat('M');
            $customerOrderChart['labels'][] = $monthName;
            
            $monthlyOrders = \App\Models\Order::where('user_id', $user->id)
                ->whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->count();
            $customerOrderChart['data'][] = $monthlyOrders;
        }
        
        // Order status distribution for customer
        $customerOrderStatus = [
            'pending' => \App\Models\Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'paid' => \App\Models\Order::where('user_id', $user->id)->where('status', 'paid')->count(),
            'shipped' => \App\Models\Order::where('user_id', $user->id)->where('status', 'shipped')->count(),
            'cancelled' => \App\Models\Order::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];
        
        // Get all categories for customer dashboard
        $categories = \App\Models\Category::orderBy('name')->get();
        
        return view('customer.dashboard', compact(
            'totalOrder', 'orderPending', 'orderSelesai', 'totalTestimoni', 'latestProducts',
            'totalSpent', 'avgCustomerRating', 'orderThisMonth', 'recentOrders',
            'customerOrderChart', 'customerOrderStatus', 'categories'
        ));
    } else {
        return app(\App\Http\Controllers\DashboardController::class)->index();
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// ================= ADMIN ROUTE GROUP =================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', AdminProductController::class)->except('show');
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('promotions', AdminPromotionController::class)->except('show');
    Route::resource('orders', AdminOrderController::class)->except('show');
    Route::get('orders/print-report', [AdminOrderController::class, 'printReport'])->name('orders.printReport');
    Route::get('orders/{id}/print-invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.printInvoice');
    Route::resource('users', AdminUserController::class)->except('show');
    Route::resource('testimonials', AdminTestimonialController::class)->except('show');
    Route::resource('payment-methods', AdminPaymentMethodController::class)->except(['show', 'create', 'edit']);
    Route::get('promotions/products-list', [AdminPromotionController::class, 'productsList'])->name('promotions.productsList');
    Route::get('products/print-report', [AdminProductController::class, 'printReport'])->name('products.printReport');
    Route::get('categories/print-report', [AdminCategoryController::class, 'printReport'])->name('categories.printReport');
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::get('payments-validation', [AdminOrderController::class, 'paymentsValidation'])->name('payments.validation');
    Route::post('payments-validation/{id}', [AdminOrderController::class, 'validatePayment'])->name('payments.validate');
    Route::resource('supplier-purchases', SupplierPurchaseController::class);
    Route::get('supplier-purchases/print-report', [SupplierPurchaseController::class, 'printReport'])->name('supplier-purchases.printReport');
    Route::get('orders/struk-keluar', [AdminOrderController::class, 'printStrukKeluar'])->name('orders.strukKeluar');
    Route::get('orders/struk-masuk', [AdminOrderController::class, 'printStrukMasuk'])->name('orders.strukMasuk');
    Route::get('products/stok-report', [AdminProductController::class, 'printStokReport'])->name('products.stokReport');
    Route::get('orders/sales-report', [AdminOrderController::class, 'printSalesReport'])->name('orders.salesReport');
    Route::get('users/print-report', [AdminUserController::class, 'printReport'])->name('users.printReport');
    Route::get('products/top-products-report', [AdminProductController::class, 'printTopProductsReport'])->name('products.topProductsReport');
    Route::get('products/sawit-unggul-report', [AdminProductController::class, 'printSawitUnggulReport'])->name('products.sawitUnggulReport');
    Route::get('products/sawit-lokal-report', [AdminProductController::class, 'printSawitLokalReport'])->name('products.sawitLokalReport');
    Route::get('products/sawit-impor-report', [AdminProductController::class, 'printSawitImporReport'])->name('products.sawitImporReport');
    Route::get('promotions/print-report', [AdminPromotionController::class, 'printReport'])->name('promotions.printReport');
    Route::resource('product-returns', App\Http\Controllers\ProductReturnController::class)->only(['index']);
    Route::get('product-returns/print-report', [App\Http\Controllers\ProductReturnController::class, 'printReport'])->name('product-returns.printReport');
    Route::get('purchase-invoices/print-report', [App\Http\Controllers\Admin\PurchaseInvoiceController::class, 'printReport'])->name('purchase-invoices.printReport');
    Route::resource('purchase-invoices', App\Http\Controllers\Admin\PurchaseInvoiceController::class);
    // Tambahkan menu khusus admin lain di sini
});

// ================= CUSTOMER ROUTE GROUP =================
Route::prefix('customer')->name('customer.')->middleware(['auth', 'customer'])->group(function () {
    Route::resource('products', CustomerProductController::class)->only(['index']);
    Route::resource('orders', CustomerOrderController::class)->except(['destroy']);
    Route::resource('payments', CustomerPaymentController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('testimonials', CustomerTestimonialController::class)->only(['index', 'create', 'store']);
    Route::get('profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [CustomerProfileController::class, 'update'])->name('profile.update');
    // Tambahkan menu khusus customer lain di sini
});

// ================= OWNER ROUTE GROUP =================
Route::prefix('owner')->name('owner.')->middleware(['auth', 'owner'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');
    Route::get('products', [\App\Http\Controllers\Owner\ProductController::class, 'index'])->name('products.index');
    Route::get('products/print-report', [\App\Http\Controllers\Owner\ProductController::class, 'printReport'])->name('products.printReport');
    Route::get('orders', [\App\Http\Controllers\Owner\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/print-report', [\App\Http\Controllers\Owner\OrderController::class, 'printReport'])->name('orders.printReport');
    Route::get('testimonials', [\App\Http\Controllers\Owner\TestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('promotions', [\App\Http\Controllers\Owner\PromotionController::class, 'index'])->name('promotions.index');
    Route::get('promotions/print-report', [\App\Http\Controllers\Owner\PromotionController::class, 'printReport'])->name('promotions.printReport');
    
    // Shared report routes - use admin controllers
    Route::get('products/stok-report', [AdminProductController::class, 'printStokReport'])->name('products.stokReport');
    Route::get('products/top-products-report', [AdminProductController::class, 'printTopProductsReport'])->name('products.topProductsReport');
    Route::get('products/sawit-unggul-report', [AdminProductController::class, 'printSawitUnggulReport'])->name('products.sawitUnggulReport');
    Route::get('products/sawit-lokal-report', [AdminProductController::class, 'printSawitLokalReport'])->name('products.sawitLokalReport');
    Route::get('products/sawit-impor-report', [AdminProductController::class, 'printSawitImporReport'])->name('products.sawitImporReport');
    Route::get('orders/sales-report', [AdminOrderController::class, 'printSalesReport'])->name('orders.salesReport');
    Route::get('orders/struk-keluar', [AdminOrderController::class, 'printStrukKeluar'])->name('orders.strukKeluar');
    Route::get('orders/struk-masuk', [AdminOrderController::class, 'printStrukMasuk'])->name('orders.strukMasuk');
    Route::get('users/print-report', [AdminUserController::class, 'printReport'])->name('users.printReport');
    Route::get('categories/print-report', [AdminCategoryController::class, 'printReport'])->name('categories.printReport');
    Route::get('purchase-invoices/print-report', [App\Http\Controllers\Admin\PurchaseInvoiceController::class, 'printReport'])->name('purchase-invoices.printReport');
    Route::get('product-returns/print-report', [App\Http\Controllers\ProductReturnController::class, 'printReport'])->name('product-returns.printReport');
    Route::get('supplier-purchases/print-report', [SupplierPurchaseController::class, 'printReport'])->name('supplier-purchases.printReport');
});

// ================= PUBLIC ROUTES =================
// Katalog produk/promosi/testimoni yg bisa diakses tanpa login
// Route::get('/katalog', [PublicProductController::class, 'index'])->name('katalog');
// Route::get('/promosi', [PublicPromotionController::class, 'index'])->name('promosi');

require __DIR__ . '/auth.php';
