@extends('admin.layouts.app')
@section('title', 'Pembelian dari Pemasok')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #c3cfe2 0%, #c3cfe2 100%);">
                <div class="card-body text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="ti ti-building-store me-2"></i>
                                Pembelian dari Pemasok
                            </h4>
                            <p class="mb-0 opacity-75">Manajemen pembelian produk dari supplier</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-dark" onclick="alert('Fitur export PDF akan segera tersedia')">
                                <i class="ti ti-file-download me-1"></i> Export PDF
                            </a>
                            <a href="{{ route('admin.supplier-purchases.create') }}" class="btn btn-dark">
                                <i class="ti ti-plus me-1"></i> Tambah Pembelian
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
                        <i class="ti ti-list me-2 text-primary"></i>Daftar Pembelian
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <tr>
                                    <th class="text-center fw-bold" style="width: 60px">#</th>
                                    <th class="fw-bold"><i class="ti ti-building me-1"></i>Supplier</th>
                                    <th class="fw-bold"><i class="ti ti-category me-1"></i>Kategori</th>
                                    <th class="fw-bold"><i class="ti ti-package me-1"></i>Produk</th>
                                    <th class="fw-bold text-end"><i class="ti ti-currency-dollar me-1"></i>Harga Satuan</th>
                                    <th class="fw-bold text-center"><i class="ti ti-hash me-1"></i>Jumlah</th>
                                    <th class="fw-bold text-end"><i class="ti ti-calculator me-1"></i>Total</th>
                                    <th class="fw-bold"><i class="ti ti-calendar me-1"></i>Tanggal</th>
                                    <th class="fw-bold"><i class="ti ti-note me-1"></i>Catatan</th>
                                    <th class="fw-bold text-center" style="width:120px;"><i class="ti ti-settings me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fw-semibold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-subtle rounded me-2">
                                                <i class="ti ti-building text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $purchase->supplier_name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            {{ $purchase->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success-subtle rounded me-2">
                                                <i class="ti ti-package text-success"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $purchase->product->name ?? '-' }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-info">
                                            Rp {{ number_format($purchase->unit_price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning-subtle text-warning">
                                            {{ $purchase->quantity }} pcs
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-success">
                                            Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="ti ti-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $purchase->note ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.supplier-purchases.edit', $purchase) }}" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.supplier-purchases.destroy', $purchase) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-building-store text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="mt-2 text-muted">Belum ada pembelian</h6>
                                            <p class="text-muted mb-0">Klik tombol "Tambah Pembelian" untuk menambah data</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($purchases, 'hasPages') && $purchases->hasPages())
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-center">
                                {{ $purchases->links('pagination::bootstrap-4') }}
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