<section class="section" id="promotions" style="background:linear-gradient(120deg,#f1f8e9 60%,#fff 100%);">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-2"><i class="bi bi-stars text-warning me-2"></i>Difiturkan</h2>
                <p class="text-muted">Informasi pilihan yang ditampilkan di homepage.</p>
            </div>
        </div>
        <div class="row g-4">
            @forelse($promotions as $promo)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg promo-card position-relative overflow-hidden" style="background:linear-gradient(120deg,#fffde7 60%,#fff9c4 100%);transition:transform .2s;">
                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2"><i class="bi bi-lightning-charge me-1"></i>Unggulan</span>
                    @if($promo->image)
                        <img src="{{ asset('storage/promotions/' . $promo->image) }}" class="card-img-top" alt="{{ $promo->title }}" style="object-fit:cover;width:100%;height:180px;border-radius:12px 12px 0 0;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-2"><i class="bi bi-award text-success me-1"></i>{{ $promo->title }}</h5>
                        <p class="card-text text-muted mb-2">{{ Str::limit($promo->description, 80) }}</p>
                        <div class="mb-2 text-muted small"><i class="bi bi-calendar-event me-1"></i>Periode: {{ $promo->start_date }} s/d {{ $promo->end_date }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">
                <img src="https://undraw.co/api/illustrations/empty?color=8bc34a" alt="No Data" style="max-width:180px;">
                <div class="mt-2">Belum ada fitur unggulan saat ini.</div>
            </div>
            @endforelse
        </div>
    </div>
    <style>
        .promo-card:hover { transform: translateY(-6px) scale(1.03); box-shadow: 0 8px 32px rgba(255,193,7,0.12); }
    </style>
</section> 