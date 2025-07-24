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
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Set default for is_active if not provided
        $validated['is_active'] = $request->has('is_active') ? true : false;

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
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Set default for is_active if not provided
        $validated['is_active'] = $request->has('is_active') ? true : false;
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

    // Report Produk Difiturkan
    public function printReport(Request $request)
    {
        $query = Promotion::with('product');
        
        // Filter berdasarkan tanggal
        if ($request->start_date) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('end_date', '<=', $request->end_date);
        }
        
        // Filter berdasarkan status aktif
        if ($request->status === 'active') {
            $query->where('start_date', '<=', now())
                  ->where('end_date', '>=', now());
        } elseif ($request->status === 'expired') {
            $query->where('end_date', '<', now());
        }
        
        $promotions = $query->orderByDesc('created_at')->get();
        
        // Hitung total produk yang difiturkan
        $totalFeatured = $promotions->count();
        $activeFeatured = $promotions->filter(function($promo) {
            return $promo->start_date <= now() && $promo->end_date >= now();
        })->count();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.promotions.report-pdf', compact('promotions', 'totalFeatured', 'activeFeatured'));
        return $pdf->download('laporan-produk-difiturkan-' . date('Y-m-d') . '.pdf');
    }
}
