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

    public function printStokReport(Request $request)
    {
        $products = \App\Models\Product::with('category')->get();
        foreach ($products as $product) {
            $stok_masuk = \App\Models\PurchaseInvoiceDetail::where('product_id', $product->id)->sum('quantity');
            $stok_keluar = $product->orderItems()->sum('quantity');
            $stok_awal = $product->stock + $stok_keluar - $stok_masuk;
            $product->stok_masuk = $stok_masuk;
            $product->stok_keluar = $stok_keluar;
            $product->stok_awal = $stok_awal;
            $product->stok_sisa = $product->stock;
        }
        $pdf = Pdf::loadView('owner.products.stok-report-pdf', compact('products'));
        return $pdf->download('laporan-stok-barang-' . date('Y-m-d') . '.pdf');
    }

    public function printTopProductsReport(Request $request)
    {
        $products = \App\Models\Product::withCount(['orderItems as total_terjual' => function($q) {
            $q->select(\Illuminate\Support\Facades\DB::raw('SUM(quantity)'));
        }])->orderByDesc('total_terjual')->take(10)->get();
        $pdf = Pdf::loadView('owner.products.top-products-report-pdf', compact('products'));
        return $pdf->download('laporan-produk-terlaris-' . date('Y-m-d') . '.pdf');
    }

    public function printSawitUnggulReport(Request $request)
    {
        $category = Category::where('name', 'Bibit Sawit Unggul')->first();
        $products = Product::with('category')->where('category_id', $category->id ?? 0)->get();
        $pdf = Pdf::loadView('owner.products.sawit-unggul-report-pdf', compact('products', 'category'));
        return $pdf->download('laporan-sawit-unggul-' . date('Y-m-d') . '.pdf');
    }

    public function printSawitLokalReport(Request $request)
    {
        $category = Category::where('name', 'Bibit Sawit Lokal')->first();
        $products = Product::with('category')->where('category_id', $category->id ?? 0)->get();
        $pdf = Pdf::loadView('owner.products.sawit-lokal-report-pdf', compact('products', 'category'));
        return $pdf->download('laporan-sawit-lokal-' . date('Y-m-d') . '.pdf');
    }

    public function printSawitImporReport(Request $request)
    {
        $category = Category::where('name', 'Bibit Sawit Impor')->first();
        $products = Product::with('category')->where('category_id', $category->id ?? 0)->get();
        $pdf = Pdf::loadView('owner.products.sawit-impor-report-pdf', compact('products', 'category'));
        return $pdf->download('laporan-sawit-impor-' . date('Y-m-d') . '.pdf');
    }
} 