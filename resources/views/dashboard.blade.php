@extends('layouts.app')
@section('title', 'Dashboard Admin - Kelapa Sawit')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Dashboard Admin</h2>
                <p class="text-muted mb-0">Selamat datang di panel admin sistem kelapa sawit</p>
            </div>
            <div class="text-end">
                <small class="text-muted">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</small>
            </div>
        </div>
    </div>
</div>
<div class="row">
  <!-- Statistik Penjualan -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">
        <div class="mb-2">
          <i class="ti ti-currency-dollar fs-2 text-success"></i>
        </div>
        <h5 class="card-title mb-1">Total Penjualan</h5>
        <h3 class="fw-bold mb-0">Rp {{ number_format($totalRevenueMonth,0,',','.') }}</h3>
        <span class="text-muted small">Bulan ini</span>
      </div>
    </div>
  </div>
  <!-- Produk -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">
        <div class="mb-2">
          <i class="ti ti-package fs-2 text-primary"></i>
        </div>
        <h5 class="card-title mb-1">Produk Bibit</h5>
        <h3 class="fw-bold mb-0">{{ $totalProduct }}</h3>
        <span class="text-muted small">Varian aktif</span>
      </div>
    </div>
  </div>
  <!-- Promosi -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">
        <div class="mb-2">
          <i class="ti ti-badge-percent fs-2 text-info"></i>
        </div>
        <h5 class="card-title mb-1">Produk Difiturkan</h5>
        <h3 class="fw-bold mb-0">{{ $totalPromo }}</h3>
        <span class="text-muted small">Produk unggulan</span>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <!-- Testimoni -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">
        <div class="mb-2">
          <i class="ti ti-star fs-2 text-warning"></i>
        </div>
        <h5 class="card-title mb-1">Testimoni</h5>
        <h3 class="fw-bold mb-0">{{ $totalTestimoni }}</h3>
        @if($totalTestimoni > 0)
          <div class="mb-1">
            @php
              $fullStars = floor($avgRating);
              $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
              $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
            @endphp
            @for($i = 0; $i < $fullStars; $i++)
              <i class="ti ti-star-filled text-warning"></i>
            @endfor
            @if($hasHalfStar)
              <span class="position-relative d-inline-block half-star">
                <i class="ti ti-star text-muted"></i>
                <i class="ti ti-star-filled text-warning position-absolute half-star-fill"></i>
              </span>
            @endif
            @for($i = 0; $i < $emptyStars; $i++)
              <i class="ti ti-star text-muted"></i>
            @endfor
            <small class="ms-1 text-muted">({{ number_format($avgRating, 1) }})</small>
          </div>
        @endif
        <span class="text-muted small">Ulasan pelanggan</span>
      </div>
    </div>
  </div>
  <!-- Pelanggan -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">
        <div class="mb-2">
          <i class="ti ti-users fs-2 text-secondary"></i>
        </div>
        <h5 class="card-title mb-1">Total Pelanggan</h5>
        <h3 class="fw-bold mb-0">{{ $totalCustomer }}</h3>
        <span class="text-muted small">Akun terdaftar</span>
      </div>
    </div>
  </div>
  <!-- Order Baru -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">
        <div class="mb-2">
          <i class="ti ti-shopping-cart fs-2 text-info"></i>
        </div>
        <h5 class="card-title mb-1">Order Hari Ini</h5>
        <h3 class="fw-bold mb-0">{{ $totalOrderToday }}</h3>
        <span class="text-muted small">Hari ini</span>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <!-- Order Pending -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">
        <div class="mb-2">
          <i class="ti ti-hourglass fs-2 text-danger"></i>
        </div>
        <h5 class="card-title mb-1">Order Pending</h5>
        <h3 class="fw-bold mb-0">{{ $orderPending }}</h3>
        <span class="text-muted small">Butuh verifikasi</span>
      </div>
    </div>
  </div>
  <!-- Order Selesai -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">
        <div class="mb-2">
          <i class="ti ti-checklist fs-2 text-success"></i>
        </div>
        <h5 class="card-title mb-1">Order Selesai</h5>
        <h3 class="fw-bold mb-0">{{ $orderSelesai }}</h3>
        <span class="text-muted small">Total selesai</span>
      </div>
    </div>
  </div>
  <!-- Admin & Owner -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">
        <div class="mb-2">
          <i class="ti ti-user-shield fs-2 text-primary"></i>
        </div>
        <h5 class="card-title mb-1">Admin & Owner</h5>
        <h3 class="fw-bold mb-0">{{ $totalAdmin }} Admin, {{ $totalOwner }} Owner</h3>
        <span class="text-muted small">Akun pengelola</span>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <!-- Produk Terlaris -->
  <div class="col-md-6 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-header bg-light fw-bold">Produk Terlaris (Top 5)</div>
      <div class="card-body p-0">
        <div class="table-responsive p-2">
          <table class="table table-sm table-bordered align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:40px">#</th>
                <th>Nama Produk</th>
                <th class="text-end">Terjual</th>
              </tr>
            </thead>
            <tbody>
              @forelse($produkTerlaris as $i => $prod)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>{{ $prod->name }}</td>
                  <td class="text-end">{{ $prod->total_terjual ?? 0 }}</td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-center">-</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- Order Terbaru -->
  <div class="col-md-6 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-header bg-light fw-bold">Order Terbaru</div>
      <div class="card-body p-0">
        <div class="table-responsive p-2">
          <table class="table table-sm table-bordered align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:40px">#</th>
                <th>User</th>
                <th>Tanggal</th>
                <th class="text-end">Total</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orderTerbaru as $i => $order)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>{{ $order->user->name ?? '-' }}</td>
                  <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : '-' }}</td>
                  <td class="text-end">Rp {{ number_format($order->total_amount,0,',','.') }}</td>
                  <td>
                    <span class="badge 
                      @if($order->status == 'pending') bg-warning
                      @elseif($order->status == 'paid') bg-success
                      @elseif($order->status == 'shipped') bg-primary
                      @elseif($order->status == 'cancelled') bg-danger
                      @else bg-secondary
                      @endif
                    ">
                      {{ ucfirst($order->status) }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center">-</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Promosi Aktif dengan Diskon -->
<div class="row">
  <div class="col-12 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-header bg-light fw-bold">
        <i class="ti ti-discount-2 me-2"></i>Promosi Aktif dengan Diskon
      </div>
      <div class="card-body p-0">
        <div class="table-responsive p-2">
          <table class="table table-sm table-bordered align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:40px">#</th>
                <th>Judul Promosi</th>
                <th>Produk</th>
                <th class="text-end">Harga Normal</th>
                <th class="text-center">Diskon</th>
                <th class="text-end">Harga Setelah Diskon</th>
                <th>Periode</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($promosiAktif as $i => $promo)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>{{ $promo->title }}</td>
                  <td>{{ $promo->product->name ?? '-' }}</td>
                  <td class="text-end">
                    @if($promo->product)
                      <span class="text-muted">Rp {{ number_format($promo->product->price, 0, ',', '.') }}</span>
                    @else
                      -
                    @endif
                  </td>
                  <td class="text-center">
                    @if($promo->discount_value > 0)
                      <span class="badge bg-danger">
                        @if($promo->discount_type === 'percentage')
                          {{ $promo->discount_value }}%
                        @else
                          Rp {{ number_format($promo->discount_value, 0, ',', '.') }}
                        @endif
                      </span>
                    @else
                      <span class="badge bg-secondary">0%</span>
                    @endif
                  </td>
                  <td class="text-end">
                    @if($promo->product && $promo->discount_value > 0)
                      <strong class="text-success">Rp {{ number_format($promo->getFinalPrice($promo->product->price, 1), 0, ',', '.') }}</strong>
                    @else
                      -
                    @endif
                  </td>
                  <td>
                    {{ $promo->start_date ? $promo->start_date->format('d/m/Y') : '-' }} - 
                    {{ $promo->end_date ? $promo->end_date->format('d/m/Y') : '-' }}
                  </td>
                  <td>
                    @if($promo->isActive())
                      <span class="badge bg-success">Aktif</span>
                    @else
                      <span class="badge bg-secondary">Tidak Aktif</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr><td colspan="8" class="text-center">Tidak ada promosi aktif</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Metrik Bisnis Modern --}}
<div class="row mb-4">
    <div class="col-3 mb-3">
        <div class="card border-0 shadow h-100 text-center">
            <div class="card-body py-4">
                <div class="mb-2"><i class="ti ti-cash fs-2 text-primary"></i></div>
                <h6 class="mb-1 text-muted">Omset Bulan Ini</h6>
                <h4 class="fw-bold mb-0" id="grossSalesMonth">{{ number_format($grossSalesMonth,0,',','.') }}</h4>
                <span class="badge bg-light text-dark small">Gross Sales</span>
            </div>
        </div>
    </div>
    <div class="col-3 mb-3">
        <div class="card border-0 shadow h-100 text-center">
            <div class="card-body py-4">
                <div class="mb-2"><i class="ti ti-trending-up fs-2 text-warning"></i></div>
                <h6 class="mb-1 text-muted">Keuntungan</h6>
                <h4 class="fw-bold mb-0" id="totalProfitMonth">{{ number_format($totalProfitMonth,0,',','.') }}</h4>
                <span class="badge bg-light text-dark small">Profit</span>
            </div>
        </div>
    </div>
    <div class="col-3 mb-3">
        <div class="card border-0 shadow h-100 text-center">
            <div class="card-body py-4">
                <div class="mb-2"><i class="ti ti-receipt fs-2 text-secondary"></i></div>
                <h6 class="mb-1 text-muted">Avg Order Value</h6>
                <h4 class="fw-bold mb-0" id="avgOrderValueMonth">{{ number_format($avgOrderValueMonth,0,',','.') }}</h4>
                <span class="badge bg-light text-dark small">Rata-rata</span>
            </div>
        </div>
    </div>
    <div class="col-3 mb-3">
        <div class="card border-0 shadow h-100 text-center">
            <div class="card-body py-4">
                <div class="mb-2"><i class="ti ti-user-plus fs-2 text-dark"></i></div>
                <h6 class="mb-1 text-muted">Customer Baru</h6>
                <h4 class="fw-bold mb-0" id="newCustomerMonth">{{ $newCustomerMonth }}</h4>
                <span class="badge bg-light text-dark small">Bulan ini</span>
            </div>
        </div>
    </div>
</div>
{{-- Grafik dan Analytics --}}
<div class="row mb-4">
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">📈 Grafik Penjualan Bulanan</h6>
                <small class="text-muted">6 bulan terakhir</small>
            </div>
            <div class="card-body" style="height: 350px;">
                <canvas id="chart-penjualan"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold">🎯 Status Order</h6>
            </div>
            <div class="card-body" style="height: 350px;">
                <canvas id="chart-order-status"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow h-100">
            <div class="card-body">
                <h6 class="card-title mb-3">Pertumbuhan Customer (6 Bulan)</h6>
                <div style="height: 300px;">
                    <canvas id="chart-customer-growth"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow h-100">
            <div class="card-header bg-light fw-bold">Produk Stok Rendah</div>
            <div class="card-body p-0">
                <div class="table-responsive p-2">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Produk</th>
                                <th class="text-end">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produkStokRendah as $i => $prod)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $prod->name }}</td>
                                    <td class="text-end"><span class="badge bg-danger">{{ $prod->stock }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">-</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow h-100">
            <div class="card-header bg-light fw-bold">Top Customer</div>
            <div class="card-body p-0">
                <div class="table-responsive p-2">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCustomerMonth as $i => $cust)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $cust['name'] }}</td>
                                    <td class="text-end">Rp {{ number_format($cust['total'],0,',','.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">-</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Shortcut Aksi Cepat --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-2">
            {{-- Semua shortcut aksi cepat dihapus karena route berbeda-beda dan bisa error --}}
        </div>
    </div>
</div>
{{-- Charts with Chart.js --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Animasi angka dengan format Rupiah
function animateValue(id, end, isRupiah = false) {
    const el = document.getElementById(id);
    if (!el) return;
    let start = 0;
    const duration = 1200;
    const step = Math.ceil(end / (duration / 16));
    function run() {
        start += step;
        if (start >= end) {
            el.innerText = isRupiah ? 'Rp ' + end.toLocaleString('id-ID') : end;
        } else {
            el.innerText = isRupiah ? 'Rp ' + start.toLocaleString('id-ID') : start;
            requestAnimationFrame(run);
        }
    }
    run();
}

// Jalankan animasi angka
animateValue('grossSalesMonth', {{ $grossSalesMonth }}, true);
animateValue('totalProfitMonth', {{ $totalProfitMonth }}, true);
animateValue('avgOrderValueMonth', {{ $avgOrderValueMonth }}, true);
animateValue('newCustomerMonth', {{ $newCustomerMonth }});
// Grafik Penjualan Bulanan dengan Chart.js
const chartPenjualanCtx = document.getElementById('chart-penjualan');
if (chartPenjualanCtx) {
    new Chart(chartPenjualanCtx, {
        type: 'line',
        data: {
            labels: @json($chartPenjualan['labels'] ?? []),
            datasets: [{
                label: 'Penjualan (Rp)',
                data: @json($chartPenjualan['data'] ?? []),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
}
// Grafik Status Order dengan Chart.js
const chartOrderStatusCtx = document.getElementById('chart-order-status');
if (chartOrderStatusCtx) {
    new Chart(chartOrderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Dibayar', 'Terkirim', 'Dibatalkan'],
            datasets: [{
                data: @json(array_values($orderStatusData)),
                backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 10,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
}
// Grafik Pertumbuhan Customer dengan Chart.js
const chartCustomerCtx = document.getElementById('chart-customer-growth');
if (chartCustomerCtx) {
    new Chart(chartCustomerCtx, {
        type: 'line',
        data: {
            labels: @json($chartCustomer['labels'] ?? []),
            datasets: [{
                label: 'Customer Baru',
                data: @json($chartCustomer['data'] ?? []),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 12
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}
</script>

<style>
.half-star {
  display: inline-block;
  position: relative;
}

.half-star-fill {
  top: 0;
  left: 0;
  width: 50%;
  overflow: hidden;
  clip-path: inset(0 50% 0 0);
}
</style>
@endpush
@endsection