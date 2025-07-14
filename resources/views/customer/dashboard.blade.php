@extends('layouts.app')
@section('title', 'Dashboard Customer')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-1">Halo, {{ auth()->user()->name }}!</h4>
        <div class="text-muted">Selamat datang di dashboard pelanggan. Berikut ringkasan aktivitas Anda.</div>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <i class="ti ti-shopping-cart" style="font-size:2rem;color:#0d6efd;"></i>
                <h5 class="fw-bold mt-2 mb-1">Total Pesanan</h5>
                <div class="fs-4">{{ $totalOrder }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <i class="ti ti-clock" style="font-size:2rem;color:#ffc107;"></i>
                <h5 class="fw-bold mt-2 mb-1">Pesanan Pending</h5>
                <div class="fs-4">{{ $orderPending }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <i class="ti ti-check" style="font-size:2rem;color:#198754;"></i>
                <h5 class="fw-bold mt-2 mb-1">Pesanan Selesai</h5>
                <div class="fs-4">{{ $orderSelesai }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow border-0 mt-4">
            <div class="card-body text-center">
                <i class="ti ti-star" style="font-size:2rem;color:#fd7e14;"></i>
                <h5 class="fw-bold mt-2 mb-1">Total Testimoni</h5>
                <div class="fs-4">{{ $totalTestimoni }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow border-0 mt-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Produk Terbaru</h5>
                <div class="row g-2">
                    @forelse($latestProducts as $product)
                    <div class="col-6 col-md-4">
                        <div class="border rounded p-2 text-center h-100">
                            <img src="{{ $product->image ? asset('storage/products/'.$product->image) : asset('assets/images/no-image.png') }}" class="img-fluid mb-2" style="max-height:80px;object-fit:contain;">
                            <div class="fw-bold small">{{ $product->name }}</div>
                            <div class="text-muted small">Rp {{ number_format($product->price,0,',','.') }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-muted">Belum ada produk terbaru.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 