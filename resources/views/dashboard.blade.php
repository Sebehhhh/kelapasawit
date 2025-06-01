@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="row">
  <!-- Statistik Penjualan -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">
        <div class="mb-2">
          <i class="ti ti-currency-dollar fs-2 text-success"></i>
        </div>
        <h5 class="card-title mb-1">Total Penjualan</h5>
        <h3 class="fw-bold mb-0">Rp 125.000.000</h3>
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
        <h3 class="fw-bold mb-0">24</h3>
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
        <h3 class="fw-bold mb-0">3</h3>
        <span class="text-muted small">Diskon berjalan</span>
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
        <h3 class="fw-bold mb-0">52</h3>
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
        <h3 class="fw-bold mb-0">146</h3>
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
        <h5 class="card-title mb-1">Order Baru</h5>
        <h3 class="fw-bold mb-0">7</h3>
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
        <h3 class="fw-bold mb-0">4</h3>
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
        <h3 class="fw-bold mb-0">98</h3>
        <span class="text-muted small">Total selesai</span>
      </div>
    </div>
  </div>
  <!-- Logo atau banner -->
  <div class="col-md-4 mb-4">
    <div class="card border-0 shadow h-100">
      <div class="card-body d-flex flex-column align-items-center justify-content-center">
        <img src="{{ asset('assets/images/logos/logo.svg') }}" alt="Logo" width="90" class="mb-2">
        <h6 class="mb-0 mt-2">Dashboard dummy — Statistik akan dinamis setelah integrasi data riil.</h6>
      </div>
    </div>
  </div>
</div>
@endsection