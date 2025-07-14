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

use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\TestimonialController as CustomerTestimonialController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'customer') {
        // Data untuk dashboard customer
        $totalOrder = \App\Models\Order::where('user_id', $user->id)->count();
        $orderPending = \App\Models\Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $orderSelesai = \App\Models\Order::where('user_id', $user->id)->where('status', 'shipped')->count();
        $totalTestimoni = \App\Models\Testimonial::where('user_id', $user->id)->count();
        $latestProducts = \App\Models\Product::orderByDesc('created_at')->take(6)->get();
        return view('customer.dashboard', compact('totalOrder', 'orderPending', 'orderSelesai', 'totalTestimoni', 'latestProducts'));
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
    Route::get('promotions/products-list', [AdminPromotionController::class, 'productsList'])->name('promotions.productsList');
    Route::get('products/print-report', [AdminProductController::class, 'printReport'])->name('products.printReport');
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::get('payments-validation', [AdminOrderController::class, 'paymentsValidation'])->name('payments.validation');
    Route::post('payments-validation/{id}', [AdminOrderController::class, 'validatePayment'])->name('payments.validate');
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
    // Tambahkan menu monitoring lain jika perlu
});

// ================= PUBLIC ROUTES =================
// Katalog produk/promosi/testimoni yg bisa diakses tanpa login
// Route::get('/katalog', [PublicProductController::class, 'index'])->name('katalog');
// Route::get('/promosi', [PublicPromotionController::class, 'index'])->name('promosi');

require __DIR__ . '/auth.php';
