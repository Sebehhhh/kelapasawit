<section class="section bg-light" id="products">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-2"><i class="bi bi-box-seam text-success me-2"></i>Produk Terbaru</h2>
                <p class="text-muted">Pilih bibit sawit unggulan, siap kirim ke seluruh Indonesia.</p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-md-4">
                <div class="card h-100 shadow-lg border-0 product-card position-relative overflow-hidden" style="transition:transform .2s;">
                    @if($product->created_at > now()->subDays(14))
                        <span class="badge bg-success position-absolute top-0 end-0 m-2">Baru</span>
                    @endif
                    <img src="{{ asset('storage/products/' . ($product->image ?? 'default.jpg')) }}" class="card-img-top" alt="{{ $product->name }}" style="object-fit:cover;width:100%;height:220px;border-radius:12px 12px 0 0;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                        <p class="card-text text-muted mb-2" style="min-height:48px;">{{ Str::limit($product->description, 80) }}</p>
                        <div class="mb-3 fw-bold text-success fs-5">Rp {{ number_format($product->price,0,',','.') }}</div>
                        <a href="{{ route('dashboard') }}" class="btn btn-success w-100 mt-auto shadow-sm" onclick="event.preventDefault(); window.location='{{ route('dashboard') }}';">
                            <i class="bi bi-cart-plus me-1"></i>Checkout
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <style>
        .product-card:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 8px 32px rgba(46,125,50,0.15);
        }
    </style>
</section> 