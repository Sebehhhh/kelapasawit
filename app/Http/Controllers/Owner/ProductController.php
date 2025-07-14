<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
        $selectedCategory = $request->category_id;
        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory);
        }
        $searchName = $request->name;
        if ($searchName) {
            $query->where('name', 'like', '%' . $searchName . '%');
        }
        $sortBy = $request->sort_by;
        $sortOrder = $request->sort_order == 'asc' ? 'asc' : 'desc';
        if (in_array($sortBy, ['price', 'stock'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderByDesc('created_at');
        }
        $products = $query->paginate(10)->appends($request->all());
        $categories = Category::all();
        return view('owner.products.index', compact('products', 'categories', 'selectedCategory', 'searchName', 'sortBy', 'sortOrder'));
    }
    public function printReport(Request $request)
    {
        $query = Product::with('category');
        $selectedCategory = $request->category_id;
        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory);
        }
        $searchName = $request->name;
        if ($searchName) {
            $query->where('name', 'like', '%' . $searchName . '%');
        }
        $sortBy = $request->sort_by;
        $sortOrder = $request->sort_order == 'asc' ? 'asc' : 'desc';
        if (in_array($sortBy, ['price', 'stock'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderByDesc('created_at');
        }
        $products = $query->get();
        $categories = Category::all();
        $data = [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'searchName' => $searchName,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ];
        $pdf = Pdf::loadView('owner.products.report-pdf', $data);
        return $pdf->download('laporan-produk-' . date('Y-m-d') . '.pdf');
    }
} 