<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::with(['orderDetail.product', 'orderDetail.order'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('customer.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil order detail milik user, order status 'shipped', belum ada testimoni
        $availableOrderDetails = OrderDetail::whereHas('order', function($q) {
            $q->where('user_id', Auth::id())->where('status', 'shipped');
        })
        ->whereDoesntHave('testimonial')
        ->with('product', 'order')
        ->get();
        return view('customer.testimonials.create', compact('availableOrderDetails'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_detail_id' => 'required|exists:order_details,id',
            'message' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        $orderDetail = OrderDetail::with('order', 'product', 'testimonial')
            ->where('id', $request->order_detail_id)
            ->firstOrFail();
        // Validasi: milik user, order shipped, belum ada testimonial
        if ($orderDetail->order->user_id !== Auth::id() || $orderDetail->order->status !== 'shipped' || $orderDetail->testimonial) {
            return back()->with('error', 'Anda tidak dapat memberi testimoni untuk transaksi ini.');
        }
        $testimonial = Testimonial::create([
            'user_id' => Auth::id(),
            'product_id' => $orderDetail->product_id,
            'order_detail_id' => $orderDetail->id,
            'message' => $request->message,
            'rating' => $request->rating,
        ]);
        return redirect()->route('customer.testimonials.index')->with('success', 'Testimoni berhasil dikirim!');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
