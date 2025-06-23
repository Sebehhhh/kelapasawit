<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderByDesc('created_at')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category (AJAX).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        $category = Category::create($validated);

        return response()->json(['message' => 'Kategori berhasil ditambah.', 'category' => $category]);
    }

    /**
     * Update the specified category (AJAX).
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update($validated);

        return response()->json(['message' => 'Kategori berhasil diupdate.']);
    }

    /**
     * Remove the specified category (AJAX).
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Validasi: Tidak hapus jika sudah ada produk terkait (optional, jika mau strict)
        // if ($category->products()->exists()) {
        //     return response()->json(['message' => 'Kategori tidak bisa dihapus karena masih punya produk!'], 422);
        // }

        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}
