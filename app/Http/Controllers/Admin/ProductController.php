<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk (dengan kategori).
     */
    public function index()
    {
        $products = Product::with('category')->orderByDesc('created_at')->paginate(10);
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
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
}
