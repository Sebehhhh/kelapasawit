<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderByDesc('created_at')->paginate(10);
        return view('owner.categories.index', compact('categories'));
    }

    public function printReport(Request $request)
    {
        $categories = Category::withCount('products')->get();
        $pdf = Pdf::loadView('owner.categories.report-pdf', compact('categories'));
        return $pdf->download('laporan-kategori-sawit-' . date('Y-m-d') . '.pdf');
    }
}