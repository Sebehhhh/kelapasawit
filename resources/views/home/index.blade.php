@php
// Data: $products, $promotions, $testimonials, $stat
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelapa Sawit UKM - Bibit, Promo, Testimoni</title>
    <link href="{{ asset('home/assets/vendors/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/vendors/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/vendors/glightbox/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/vendors/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/vendors/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="site-wrap">
        @include('home.partials.header')
        <main>
            @include('home.partials.hero')
            @include('home.partials.stats', ['stat' => $stat])
            @include('home.partials.products', ['products' => $products])
            @include('home.partials.promotions', ['promotions' => $promotions])
            @include('home.partials.testimonials', ['testimonials' => $testimonials])
            @include('home.partials.contact_cs')
            <section class="section" id="alamat" style="background:linear-gradient(120deg,#fffde7 60%,#fff 100%);">
                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card shadow-lg border-0 p-4 text-center" style="border-radius:18px;">
                                <h2 class="fw-bold mb-3"><i class="bi bi-geo-alt text-danger me-2"></i>Alamat Kami</h2>
                                <p class="mb-3 text-muted">Jl. Contoh Alamat No. 123, Kecamatan Sawit, Kabupaten Perkebunan, Provinsi Indonesia</p>
                                <div class="ratio ratio-16x9 rounded-4 overflow-hidden">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.934123456789!2d110.1234567!3d-7.1234567!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMDcnMjQuNSJTIDExMMKwMDcnMjQuNSJF!5e0!3m2!1sid!2sid!4v1710000000000!5m2!1sid!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        @include('home.partials.footer')
    </div>
    <script src="{{ asset('home/assets/vendors/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('home/assets/vendors/glightbox/glightbox.min.js') }}"></script>
    <script src="{{ asset('home/assets/vendors/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('home/assets/vendors/aos/aos.js') }}"></script>
    <script src="{{ asset('home/assets/js/custom.js') }}"></script>
    <script>AOS.init();</script>
</body>
</html> 