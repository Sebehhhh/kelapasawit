@extends('layouts.app')
@section('title', 'Kelola Barang Masuk')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="ti ti-truck-delivery me-2"></i>
                                Kelola Barang Masuk
                            </h4>
                            <p class="mb-0 opacity-75">Manajemen pembelian dan penerimaan barang dari pemasok</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.purchase-invoices.printReport') }}" class="btn btn-light">
                                <i class="ti ti-file-download me-1"></i> Export PDF
                            </a>
                            <a href="{{ route('admin.purchase-invoices.create') }}" class="btn btn-light">
                                <i class="ti ti-plus me-1"></i> Tambah Transaksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="ti ti-list me-2 text-primary"></i>Daftar Transaksi Pembelian
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <tr>
                                    <th class="text-center fw-bold" style="width: 60px">#</th>
                                    <th class="fw-bold"><i class="ti ti-building-store me-1"></i>Supplier</th>
                                    <th class="fw-bold"><i class="ti ti-calendar me-1"></i>Tanggal</th>
                                    <th class="fw-bold text-end"><i class="ti ti-currency-dollar me-1"></i>Total</th>
                                    <th class="fw-bold"><i class="ti ti-note me-1"></i>Catatan</th>
                                    <th class="fw-bold"><i class="ti ti-package me-1"></i>Detail Produk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $inv)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fw-semibold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-subtle rounded me-2">
                                                <i class="ti ti-building-store text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $inv->supplier_name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="ti ti-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($inv->purchase_date)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-success">
                                            Rp {{ number_format($inv->total, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $inv->note ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($inv->details as $d)
                                                <span class="badge bg-secondary-subtle text-secondary small">
                                                    {{ $d->product->name ?? '-' }} ({{ $d->quantity }}x @ Rp{{ number_format($d->unit_price,0,',','.') }})
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-truck-delivery text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="mt-2 text-muted">Belum ada transaksi barang masuk</h6>
                                            <p class="text-muted mb-0">Klik tombol "Tambah Transaksi" untuk menambah data</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($invoices, 'hasPages') && $invoices->hasPages())
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-center">
                                {{ $invoices->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection 