<?php

namespace App\Http\Controllers;

use App\Models\ProductReturn;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returns = \App\Models\ProductReturn::with(['order', 'product'])->orderByDesc('return_date')->get();
        return view('admin.product-returns.index', compact('returns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductReturn $productReturn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductReturn $productReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductReturn $productReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReturn $productReturn)
    {
        //
    }

    public function printReport(Request $request)
    {
        $returns = \App\Models\ProductReturn::with(['order', 'product'])->orderByDesc('return_date')->get();
        $pdf = Pdf::loadView('admin.product-returns.report-pdf', compact('returns'));
        return $pdf->download('laporan-retur-barang-' . date('Y-m-d') . '.pdf');
    }
}
