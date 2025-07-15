<section class="section bg-light" id="testimonials">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-2"><i class="bi bi-chat-quote text-primary me-2"></i>Testimoni Pelanggan</h2>
                <p class="text-muted">Apa kata mereka tentang layanan & produk kami?</p>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($testimonials as $testi)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow testimonial-card p-3 position-relative" style="border-radius:18px;transition:transform .2s;">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-size:1.5rem;">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div>
                            <strong>{{ $testi->user->name ?? '-' }}</strong>
                            <div class="text-muted small">{{ $testi->product->name ?? '-' }}</div>
                        </div>
                    </div>
                    <blockquote class="blockquote mb-2 mt-2 px-2">
                        <p class="mb-0 fs-5 text-dark">“{{ $testi->message }}”</p>
                    </blockquote>
                    <div class="mb-2">
                        @for($i=1;$i<=5;$i++)
                            <i class="bi bi-star{{ $i <= $testi->rating ? '-fill text-warning' : '' }}"></i>
                        @endfor
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <style>
            .testimonial-card:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 8px 32px rgba(33,150,243,0.10); }
        </style>
    </div>
</section> 