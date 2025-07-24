<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\SupplierPurchase;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierPurchase::with('user');
        
        // Filter by date if provided
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $purchases = $query->orderByDesc('created_at')->paginate(10)->appends($request->all());
        return view('owner.supplier-purchases.index', compact('purchases'));
    }

    public function printReport(Request $request)
    {
        $query = SupplierPurchase::with('user');
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $purchases = $query->orderByDesc('created_at')->get();
        $pdf = Pdf::loadView('owner.supplier-purchases.report-pdf', compact('purchases'));
        return $pdf->download('laporan-pembelian-supplier-' . date('Y-m-d') . '.pdf');
    }
}