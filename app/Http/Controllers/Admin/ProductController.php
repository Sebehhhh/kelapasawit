<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk (dengan kategori).
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter kategori
        $selectedCategory = $request->category_id;
        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory);
        }

        // Filter nama produk
        $searchName = $request->name;
        if ($searchName) {
            $query->where('name', 'like', '%' . $searchName . '%');
        }

        // Sorting
        $sortBy = $request->sort_by;
        $sortOrder = $request->sort_order == 'asc' ? 'asc' : 'desc';
        if (in_array($sortBy, ['price', 'stock'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderByDesc('created_at');
        }

        $products = $query->paginate(10)->appends($request->all());
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories', 'selectedCategory', 'searchName', 'sortBy', 'sortOrder'));
    }

    /**
     * Store produk baru (AJAX modal).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // Handle upload gambar jika ada
        if ($request->hasFile('image')) {
            $filename = uniqid() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('products', $filename);
            $validated['image'] = $filename;
        }

        $product = Product::create($validated);

        return response()->json(['message' => 'Produk berhasil ditambah.', 'product' => $product]);
    }

    /**
     * Update produk (AJAX modal).
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // Jika upload gambar baru, hapus yang lama
        if ($request->hasFile('image')) {
            if ($product->image && Storage::exists('products/' . $product->image)) {
                Storage::delete('products/' . $product->image);
            }
            $filename = uniqid() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('products', $filename);
            $validated['image'] = $filename;
        }

        $product->update($validated);

        return response()->json(['message' => 'Produk berhasil diupdate.']);
    }

    /**
     * Hapus produk (AJAX modal).
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus file gambar jika ada
        if ($product->image && Storage::exists('products/' . $product->image)) {
            Storage::delete('products/' . $product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }

    /**
     * Cetak laporan produk dalam format PDF
     */
    public function printReport(Request $request)
    {
        $query = Product::with('category');

        // Filter kategori
        $selectedCategory = $request->category_id;
        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory);
        }

        // Filter nama produk
        $searchName = $request->name;
        if ($searchName) {
            $query->where('name', 'like', '%' . $searchName . '%');
        }

        // Sorting
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
        $pdf = Pdf::loadView('admin.products.report-pdf', $data);
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
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.products.stok-report-pdf', compact('products'));
        return $pdf->download('laporan-stok-barang-' . date('Y-m-d') . '.pdf');
    }

    public function printTopProductsReport(Request $request)
    {
        $products = \App\Models\Product::withCount(['orderItems as total_terjual' => function($q) {
            $q->select(DB::raw('SUM(quantity)'));
        }])->orderByDesc('total_terjual')->take(10)->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.products.top-products-report-pdf', compact('products'));
        return $pdf->download('laporan-produk-terlaris-' . date('Y-m-d') . '.pdf');
    }

    // Report Kategori Sawit Unggul
    public function printSawitUnggulReport(Request $request)
    {
        $category = Category::where('name', 'Bibit Sawit Unggul')->first();
        $products = Product::with('category')->where('category_id', $category->id ?? 0)->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.products.sawit-unggul-report-pdf', compact('products', 'category'));
        return $pdf->download('laporan-sawit-unggul-' . date('Y-m-d') . '.pdf');
    }

    // Report Kategori Sawit Lokal
    public function printSawitLokalReport(Request $request)
    {
        $category = Category::where('name', 'Bibit Sawit Lokal')->first();
        $products = Product::with('category')->where('category_id', $category->id ?? 0)->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.products.sawit-lokal-report-pdf', compact('products', 'category'));
        return $pdf->download('laporan-sawit-lokal-' . date('Y-m-d') . '.pdf');
    }

    // Report Kategori Sawit Impor
    public function printSawitImporReport(Request $request)
    {
        $category = Category::where('name', 'Bibit Sawit Impor')->first();
        $products = Product::with('category')->where('category_id', $category->id ?? 0)->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.products.sawit-impor-report-pdf', compact('products', 'category'));
        return $pdf->download('laporan-sawit-impor-' . date('Y-m-d') . '.pdf');
    }
}
