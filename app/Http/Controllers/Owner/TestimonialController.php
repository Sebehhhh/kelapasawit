<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Product;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::with(['user', 'product']);
        if ($request->filled('user')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }
        if ($request->filled('rating_sort') && in_array($request->rating_sort, ['asc', 'desc'])) {
            $query->orderBy('rating', $request->rating_sort);
        } else {
            $query->orderByDesc('created_at');
        }
        $testimonials = $query->paginate(10)->appends($request->all());
        $users = User::all();
        $products = Product::all();
        return view('owner.testimonials.index', compact('testimonials', 'users', 'products'));
    }
} 