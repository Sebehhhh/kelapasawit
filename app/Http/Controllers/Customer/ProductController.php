<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource (katalog produk).
     */
    public function index()
    {
        // Eager load kategori dan promosi aktif untuk tampilan katalog
        $products = Product::with(['category', 'promotions' => function($query) {
                $query->active(); // Only load active promotions
            }])
            ->orderByDesc('created_at')
            ->paginate(9); // Bisa sesuaikan per page

        // Get all categories for filter dropdown
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('customer.products.index', compact('products', 'categories'));
    }

}
