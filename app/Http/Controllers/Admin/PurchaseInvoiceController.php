<?php

namespace App\Http\Controllers\Admin;

use App\Models\PurchaseInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class PurchaseInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $invoices = PurchaseInvoice::with(['details.product'])->orderByDesc('purchase_date')->get();
            return view('admin.purchase-invoices.index', compact('invoices'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading purchase invoices: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = \App\Models\Product::all();
        return view('admin.purchase-invoices.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'note' => 'nullable|string',
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'unit_price' => 'required|array|min:1',
            'unit_price.*' => 'required|numeric|min:0',
        ]);
        DB::beginTransaction();
        try {
            $invoice = \App\Models\PurchaseInvoice::create([
                'supplier_name' => $validated['supplier_name'],
                'purchase_date' => $validated['purchase_date'],
                'note' => $validated['note'] ?? null,
                'total' => 0, // akan diupdate setelah detail
            ]);
            $total = 0;
            foreach ($validated['product_id'] as $i => $pid) {
                $qty = $validated['quantity'][$i];
                $price = $validated['unit_price'][$i];
                $subtotal = $qty * $price;
                $invoice->details()->create([
                    'product_id' => $pid,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $subtotal,
                ]);
                // Stok produk akan otomatis bertambah via model event PurchaseInvoiceDetail
                $total += $subtotal;
            }
            $invoice->update(['total' => $total]);
            DB::commit();
            return redirect()->route('admin.purchase-invoices.index')->with('success', 'Transaksi barang masuk berhasil disimpan & stok produk bertambah.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseInvoice $purchaseInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseInvoice $purchaseInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseInvoice $purchaseInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseInvoice $purchaseInvoice)
    {
        //
    }

    public function printReport(Request $request)
    {
        try {
            $invoices = PurchaseInvoice::with(['details.product'])->orderByDesc('purchase_date')->get();
            $pdf = Pdf::loadView('admin.purchase-invoices.report-pdf', compact('invoices'));
            return $pdf->download('laporan-barang-masuk-' . date('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }
}
