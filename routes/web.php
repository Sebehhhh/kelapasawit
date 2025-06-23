<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;

use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\TestimonialController as CustomerTestimonialController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ================= ADMIN ROUTE GROUP =================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('promotions', AdminPromotionController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::resource('users', AdminUserController::class);
    Route::resource('reports', AdminReportController::class);
    Route::resource('testimonials', AdminTestimonialController::class);
    // Tambahkan menu khusus admin lain di sini
});

// ================= CUSTOMER ROUTE GROUP =================
Route::prefix('customer')->name('customer.')->middleware(['auth', 'customer'])->group(function () {
    Route::resource('products', CustomerProductController::class)->only(['index']);
    Route::resource('orders', CustomerOrderController::class)->except(['destroy']);
    Route::resource('payments', CustomerPaymentController::class)->only(['create', 'store', 'show']);
    Route::resource('testimonials', CustomerTestimonialController::class)->only(['index', 'create', 'store']);
    Route::get('profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [CustomerProfileController::class, 'update'])->name('profile.update');
    // Tambahkan menu khusus customer lain di sini
});

// ================= PUBLIC ROUTES =================
// Katalog produk/promosi/testimoni yg bisa diakses tanpa login
// Route::get('/katalog', [PublicProductController::class, 'index'])->name('katalog');
// Route::get('/promosi', [PublicPromotionController::class, 'index'])->name('promosi');

require __DIR__ . '/auth.php';
