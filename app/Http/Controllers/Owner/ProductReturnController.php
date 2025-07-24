<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ProductReturn;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductReturn::with(['product', 'user']);
        
        // Filter by date if provided
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $returns = $query->orderByDesc('created_at')->paginate(10)->appends($request->all());
        return view('owner.product-returns.index', compact('returns'));
    }

    public function printReport(Request $request)
    {
        $query = ProductReturn::with(['product', 'user']);
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $returns = $query->orderByDesc('created_at')->get();
        $pdf = Pdf::loadView('owner.product-returns.report-pdf', compact('returns'));
        return $pdf->download('laporan-retur-produk-' . date('Y-m-d') . '.pdf');
    }
}