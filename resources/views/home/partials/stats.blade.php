<section class="section py-5" style="background:linear-gradient(120deg,#f9fbe7 60%,#e8f5e9 100%);">
    <div class="container">
        <div class="row text-center g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card border-0 shadow h-100 py-4 stat-card" style="border-radius:18px;transition:transform .2s;">
                    <div class="mb-2"><i class="bi bi-box2 text-success" style="font-size:2.5rem;"></i></div>
                    <h2 class="fw-bold mb-1 text-success">{{ $stat['total_products'] }}</h2>
                    <p class="mb-0 text-muted">Produk Bibit</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow h-100 py-4 stat-card" style="border-radius:18px;transition:transform .2s;">
                    <div class="mb-2"><i class="bi bi-people text-primary" style="font-size:2.5rem;"></i></div>
                    <h2 class="fw-bold mb-1 text-primary">{{ $stat['total_customers'] }}</h2>
                    <p class="mb-0 text-muted">Pelanggan</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow h-100 py-4 stat-card" style="border-radius:18px;transition:transform .2s;">
                    <div class="mb-2"><i class="bi bi-chat-quote text-warning" style="font-size:2.5rem;"></i></div>
                    <h2 class="fw-bold mb-1 text-warning">{{ $stat['total_testimonials'] }}</h2>
                    <p class="mb-0 text-muted">Testimoni</p>
                </div>
            </div>
        </div>
        <style>
            .stat-card:hover { transform: translateY(-4px) scale(1.03); box-shadow: 0 8px 32px rgba(76,175,80,0.10); }
        </style>
    </div>
</section> 