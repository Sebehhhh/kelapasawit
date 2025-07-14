<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Product;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::with(['user', 'product'])->orderByDesc('created_at')->paginate(10);
        $users = User::all();
        $products = Product::all();
        return view('admin.testimonials.index', compact('testimonials', 'users', 'products'));
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
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'message' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        $testi = Testimonial::create($validated);
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Testimoni berhasil ditambah.', 'testimonial' => $testi]);
        }
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil ditambah.');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $testi = Testimonial::findOrFail($id);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'message' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        $testi->update($validated);
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Testimoni berhasil diupdate.', 'testimonial' => $testi]);
        }
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $testi = Testimonial::findOrFail($id);
        $testi->delete();
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Testimoni berhasil dihapus.']);
        }
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil dihapus.');
    }
}
