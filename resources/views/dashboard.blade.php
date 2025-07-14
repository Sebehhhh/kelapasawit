@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="row">
  <div class="col-12 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body">
        <h5 class="card-title mb-3">Grafik Penjualan Bulanan</h5>
        <div id="chart-penjualan" style="height:320px;"></div>
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
        <h5 class="card-title mb-1">Promosi Aktif</h5>
        <h3 class="fw-bold mb-0">{{ $totalPromo }}</h3>
        <span class="text-muted small">Promosi unggulan</span>
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
        <div class="table-responsive">
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
        <div class="table-responsive">
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
                  <td class="text-end">Rp{{ number_format($order->total_amount,0,',','.') }}</td>
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
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var options = {
      chart: {
        type: 'line',
        height: 320,
        toolbar: { show: false }
      },
      series: [{
        name: 'Total Penjualan',
        data: @json($chartPenjualan['data'] ?? [])
      }],
      xaxis: {
        categories: @json($chartPenjualan['labels'] ?? []),
        labels: { style: { fontSize: '13px' } }
      },
      stroke: { curve: 'smooth', width: 3 },
      colors: ['#198754'],
      markers: { size: 4 },
      dataLabels: { enabled: false },
      grid: { borderColor: '#eee' },
      tooltip: { y: { formatter: val => 'Rp ' + val.toLocaleString('id-ID') } }
    };
    if(document.getElementById('chart-penjualan')) {
      new ApexCharts(document.getElementById('chart-penjualan'), options).render();
    }
  });
</script>
@endpush
@endsection