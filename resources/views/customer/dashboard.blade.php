@extends('layouts.app')
@section('title', 'Dashboard Customer')
@section('content')

{{-- Hero Welcome Section --}}
<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 200px;">
            <div class="card-body d-flex align-items-center text-white">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle bg-white bg-opacity-20 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%;">
                            <i class="ti ti-user-check fs-2 text-white"></i>
                        </div>
                        <div>
                            <h2 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h2>
                            <p class="mb-0 opacity-90">{{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>
                    <p class="mb-0 opacity-80">Kelola pesanan Anda dan jelajahi produk bibit kelapa sawit berkualitas tinggi</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Stats Cards --}}
<div class="row g-4 mb-5">
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="ti ti-shopping-cart fs-2 text-primary"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-1">Total Pesanan</h5>
                <h3 class="fw-bold text-primary mb-1 counter" data-target="{{ $totalOrder }}">0</h3>
                <small class="text-muted">Semua waktu</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="ti ti-clock fs-2 text-warning"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-1">Menunggu Konfirmasi</h5>
                <h3 class="fw-bold text-warning mb-1 counter" data-target="{{ $orderPending }}">0</h3>
                <small class="text-muted">Perlu diproses</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="ti ti-check fs-2 text-success"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-1">Pesanan Selesai</h5>
                <h3 class="fw-bold text-success mb-1 counter" data-target="{{ $orderSelesai }}">0</h3>
                <small class="text-muted">Berhasil diterima</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="ti ti-star fs-2 text-info"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-1">Ulasan Diberikan</h5>
                <h3 class="fw-bold text-info mb-1 counter" data-target="{{ $totalTestimoni }}">0</h3>
                <small class="text-muted">Testimoni Anda</small>
            </div>
        </div>
    </div>
</div>

{{-- Order Summary & Statistics --}}
<div class="row mb-5">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="fw-bold mb-0">
                    <i class="ti ti-chart-line me-2 text-primary"></i>Riwayat Pesanan Anda (6 Bulan Terakhir)
                </h5>
            </div>
            <div class="card-body" style="height: 300px;">
                <canvas id="customerOrderChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light border-0">
                <h5 class="fw-bold mb-0">
                    <i class="ti ti-info-circle me-2 text-info"></i>Ringkasan Akun
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="text-muted">Member sejak:</span>
                    <span class="fw-semibold">{{ auth()->user()->created_at->format('M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="text-muted">Status akun:</span>
                    <span class="badge bg-success">Aktif</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="text-muted">Total belanja:</span>
                    <span class="fw-semibold text-primary">Rp {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="text-muted">Rata-rata rating:</span>
                    <div>
                        @if($totalTestimoni > 0)
                            @for($i = 1; $i <= 5; $i++)
                                <i class="ti ti-star-filled text-warning small"></i>
                            @endfor
                            <small class="text-muted ms-1">({{ $avgCustomerRating ?? 5.0 }})</small>
                        @else
                            <small class="text-muted">Belum ada ulasan</small>
                        @endif
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Pesanan bulan ini:</span>
                    <span class="fw-semibold">{{ $orderThisMonth ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders & Order Status --}}
<div class="row mb-5">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="fw-bold mb-0">
                    <i class="ti ti-clock-hour-4 me-2 text-warning"></i>Pesanan Terbaru
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Tanggal</th>
                                <th class="border-0">Total</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Items</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders ?? [] as $order)
                            <tr>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="fw-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge 
                                        @if($order->status == 'pending') bg-warning
                                        @elseif($order->status == 'paid') bg-info
                                        @elseif($order->status == 'shipped') bg-success
                                        @else bg-danger
                                        @endif
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->details->count() }} item</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="ti ti-inbox fs-2 d-block mb-2"></i>
                                    Belum ada pesanan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light border-0">
                <h5 class="fw-bold mb-0">
                    <i class="ti ti-chart-donut me-2 text-success"></i>Status Pesanan
                </h5>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="height: 250px;">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Latest Products Section --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="ti ti-leaf me-2 text-success"></i>Produk Bibit Terbaru
                    </h5>
                    <a href="{{ route('customer.products.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="ti ti-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @forelse($latestProducts as $product)
                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="card border-0 shadow-sm h-100 product-card">
                            <div class="position-relative">
                                <img src="{{ $product->image ? asset('storage/products/'.$product->image) : asset('assets/images/no-image.png') }}" 
                                     class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-success rounded-pill">Tersedia</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-2 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="text-primary fw-bold">Rp {{ number_format($product->price,0,',','.') }}</div>
                                    <small class="text-muted">Stok: {{ $product->stock }}</small>
                                </div>
                                <p class="text-muted small mb-3" style="height: 40px; overflow: hidden;">
                                    {{ Str::limit($product->description ?? 'Bibit kelapa sawit berkualitas tinggi', 50) }}
                                </p>
                                <button class="btn btn-sm btn-primary w-100" onclick="showProductModal({{ $product->id }}, '{{ $product->name }}', '{{ $product->description }}', {{ $product->price }}, {{ $product->stock }}, '{{ $product->image ? asset('storage/products/'.$product->image) : asset('assets/images/no-image.png') }}', '{{ $product->category->name ?? 'Tidak ada kategori' }}')">
                                    <i class="ti ti-eye me-1"></i>Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="ti ti-package-off fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada produk terbaru</h5>
                            <p class="text-muted">Produk akan ditampilkan di sini ketika tersedia</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.quick-action-btn {
    transition: all 0.3s ease;
    border-radius: 12px !important;
}

.quick-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.product-card {
    transition: all 0.3s ease;
    border-radius: 12px !important;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.counter {
    font-size: 2.5rem !important;
}

.avatar-circle {
    backdrop-filter: blur(10px);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease forwards;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Counter animation
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 1000;
        const step = target / (duration / 16); // 60fps
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 16);
    });
    
    // Add stagger animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Customer Order Chart (Line Chart)
    const customerOrderCtx = document.getElementById('customerOrderChart');
    if (customerOrderCtx) {
        new Chart(customerOrderCtx, {
            type: 'line',
            data: {
                labels: @json($customerOrderChart['labels'] ?? []),
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: @json($customerOrderChart['data'] ?? []),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    // Order Status Chart (Doughnut)
    const orderStatusCtx = document.getElementById('orderStatusChart');
    if (orderStatusCtx) {
        const statusData = @json($customerOrderStatus ?? []);
        
        new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Dibayar', 'Selesai', 'Dibatalkan'],
                datasets: [{
                    data: [
                        statusData?.pending || 0,
                        statusData?.paid || 0, 
                        statusData?.shipped || 0,
                        statusData?.cancelled || 0
                    ],
                    backgroundColor: ['#f59e0b', '#06b6d4', '#10b981', '#ef4444'],
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
});

// Product Modal Function
function showProductModal(id, name, description, price, stock, image, category) {
    document.getElementById('modalProductName').textContent = name;
    document.getElementById('modalProductDescription').textContent = description || 'Tidak ada deskripsi';
    document.getElementById('modalProductPrice').textContent = 'Rp ' + price.toLocaleString('id-ID');
    document.getElementById('modalProductStock').textContent = stock + ' tersedia';
    document.getElementById('modalProductImage').src = image;
    document.getElementById('modalProductCategory').textContent = category;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
    modal.show();
}
</script>
@endpush

{{-- Product Detail Modal --}}
<div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productDetailModalLabel">Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img id="modalProductImage" src="" alt="Product Image" class="img-fluid rounded" style="width: 100%; height: 300px; object-fit: cover;">
                    </div>
                    <div class="col-md-6">
                        <h4 id="modalProductName" class="fw-bold mb-3"></h4>
                        <div class="mb-3">
                            <span class="badge bg-primary" id="modalProductCategory"></span>
                        </div>
                        <p id="modalProductDescription" class="text-muted mb-3"></p>
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">Harga:</small>
                                <div class="fw-bold text-primary fs-5" id="modalProductPrice"></div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Stok:</small>
                                <div class="fw-bold" id="modalProductStock"></div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            Untuk melakukan pembelian, silakan kunjungi halaman <a href="{{ route('customer.products.index') }}" class="alert-link">Katalog Produk</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary">
                    <i class="ti ti-shopping-cart me-1"></i>Lihat di Katalog
                </a>
            </div>
        </div>
    </div>
</div>

@endsection 