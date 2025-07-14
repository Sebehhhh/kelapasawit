<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promotions = Promotion::with('product')->orderByDesc('created_at')->paginate(10);
        $products = Product::all();
        return view('admin.promotions.index', compact('promotions', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.promotions.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $filename = uniqid() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('promotions', $filename, 'public');
            $validated['image'] = $filename;
        }

        $promo = Promotion::create($validated);
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Promosi berhasil ditambah.', 'promotion' => $promo]);
        }
        return redirect()->route('admin.promotions.index')->with('success', 'Promosi berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $products = Product::all();
        return view('admin.promotions.edit', compact('promotion', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        if ($request->hasFile('image')) {
            if ($promotion->image && Storage::disk('public')->exists('promotions/' . $promotion->image)) {
                Storage::disk('public')->delete('promotions/' . $promotion->image);
            }
            $filename = uniqid() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('promotions', $filename, 'public');
            $validated['image'] = $filename;
        }
        $promotion->update($validated);
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Promosi berhasil diupdate.', 'promotion' => $promotion]);
        }
        return redirect()->route('admin.promotions.index')->with('success', 'Promosi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        if ($promotion->image && Storage::disk('public')->exists('promotions/' . $promotion->image)) {
            Storage::disk('public')->delete('promotions/' . $promotion->image);
        }
        $promotion->delete();
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Promosi berhasil dihapus.']);
        }
        return redirect()->route('admin.promotions.index')->with('success', 'Promosi berhasil dihapus.');
    }

    // Untuk dropdown produk via AJAX
    public function productsList()
    {
        $products = Product::select('id', 'name')->get();
        return response()->json($products);
    }
}
