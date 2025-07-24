<?php

namespace App\Http\Controllers;

use App\Models\SupplierPurchase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = SupplierPurchase::with(['category', 'product'])->orderByDesc('purchase_date')->get();
        return view('admin.supplier-purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::all();
        return view('admin.supplier-purchases.create', compact('categories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'product_id'    => 'required|exists:products,id',
            'unit_price'    => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:1',
            'purchase_date' => 'required|date',
            'note'          => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $total_price = $validated['unit_price'] * $validated['quantity'];
            $purchase = SupplierPurchase::create([
                ...$validated,
                'total_price' => $total_price,
            ]);
            // Update stok produk otomatis
            $product->increment('stock', $validated['quantity']);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pembelian dari pemasok berhasil disimpan & stok produk bertambah.', 'purchase' => $purchase]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan pembelian: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplierPurchase $supplierPurchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $purchase = SupplierPurchase::findOrFail($id);
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::all();
        return view('admin.supplier-purchases.edit', compact('purchase', 'categories', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $purchase = SupplierPurchase::findOrFail($id);
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'product_id'    => 'required|exists:products,id',
            'unit_price'    => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:1',
            'purchase_date' => 'required|date',
            'note'          => 'nullable|string',
        ]);
        $oldQty = $purchase->quantity;
        $newQty = $validated['quantity'];
        $product = \App\Models\Product::lockForUpdate()->findOrFail($validated['product_id']);
        $purchase->update([
            ...$validated,
            'total_price' => $validated['unit_price'] * $validated['quantity'],
        ]);
        // Update stok jika jumlah berubah
        if ($oldQty != $newQty) {
            $diff = $newQty - $oldQty;
            $product->increment('stock', $diff);
        }
        return redirect()->route('admin.supplier-purchases.index')->with('success', 'Data pembelian berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $purchase = SupplierPurchase::findOrFail($id);
        $product = \App\Models\Product::lockForUpdate()->findOrFail($purchase->product_id);
        // Kurangi stok produk sesuai jumlah pembelian yang dihapus
        $product->decrement('stock', $purchase->quantity);
        $purchase->delete();
        return redirect()->route('admin.supplier-purchases.index')->with('success', 'Data pembelian berhasil dihapus & stok produk dikurangi.');
    }

    public function printReport(Request $request)
    {
        $purchases = SupplierPurchase::with(['category', 'product'])
            ->orderByDesc('purchase_date')->get();
        $pdf = Pdf::loadView('admin.supplier-purchases.report-pdf', compact('purchases'));
        return $pdf->download('laporan-pembelian-pemasok-' . date('Y-m-d') . '.pdf');
    }
}
