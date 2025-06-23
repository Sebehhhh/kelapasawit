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
        // Eager load kategori untuk tampilan katalog
        $products = Product::with('category')
            ->orderByDesc('created_at')
            ->paginate(9); // Bisa sesuaikan per page

        return view('customer.products.index', compact('products'));
    }

}
