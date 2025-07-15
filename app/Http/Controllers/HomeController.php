<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promotion;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::orderByDesc('created_at')->take(9)->get();
        // Contoh mengambil data promosi aktif, urut terbaru, maksimal 3
        $promotions = Promotion::orderByDesc('created_at')->take(3)->get();
        $testimonials = Testimonial::with('user', 'product')->orderByDesc('created_at')->take(5)->get();
        $stat = [
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_testimonials' => Testimonial::count(),
        ];
        return view('home.index', compact('products', 'promotions', 'testimonials', 'stat'));
    }
}
