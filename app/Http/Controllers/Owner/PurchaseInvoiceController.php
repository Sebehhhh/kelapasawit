<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\PurchaseInvoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseInvoice::with(['details.product']);
        
        // Filter by date if provided
        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }
        
        $invoices = $query->orderByDesc('purchase_date')->paginate(10)->appends($request->all());
        return view('owner.purchase-invoices.index', compact('invoices'));
    }

    public function printReport(Request $request)
    {
        $query = PurchaseInvoice::with(['details.product']);
        
        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }
        
        $invoices = $query->orderByDesc('purchase_date')->get();
        $pdf = Pdf::loadView('owner.purchase-invoices.report-pdf', compact('invoices'));
        return $pdf->download('laporan-invoice-pembelian-' . date('Y-m-d') . '.pdf');
    }
}