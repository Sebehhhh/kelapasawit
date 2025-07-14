<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Product;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('product')->orderByDesc('created_at')->paginate(10);
        $products = Product::all();
        return view('owner.promotions.index', compact('promotions', 'products'));
    }

    public function printReport()
    {
        $promotions = \App\Models\Promotion::with('product')->orderByDesc('created_at')->get();
        $products = \App\Models\Product::all();
        return view('owner.promotions.report-pdf', compact('promotions', 'products'));
    }
} 