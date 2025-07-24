@extends('admin.layouts.app')
@section('title', 'Kelola Retur Barang')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                <div class="card-body text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="ti ti-package-export me-2"></i>
                                Kelola Retur Barang
                            </h4>
                            <p class="mb-0 opacity-75">Manajemen retur dan pengembalian produk</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-dark" onclick="alert('Fitur export PDF akan segera tersedia')">
                                <i class="ti ti-file-download me-1"></i> Export PDF
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
                        <i class="ti ti-list me-2 text-primary"></i>Daftar Retur Barang
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <tr>
                                    <th class="text-center fw-bold" style="width: 60px">#</th>
                                    <th class="fw-bold"><i class="ti ti-calendar me-1"></i>Tanggal Retur</th>
                                    <th class="fw-bold"><i class="ti ti-shopping-cart me-1"></i>Order</th>
                                    <th class="fw-bold"><i class="ti ti-package me-1"></i>Produk</th>
                                    <th class="fw-bold text-center"><i class="ti ti-hash me-1"></i>Qty</th>
                                    <th class="fw-bold"><i class="ti ti-message me-1"></i>Alasan</th>
                                    <th class="fw-bold text-center"><i class="ti ti-info-circle me-1"></i>Status</th>
                                    <th class="fw-bold"><i class="ti ti-note me-1"></i>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returns as $retur)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fw-semibold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="ti ti-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($retur->return_date)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            <i class="ti ti-hash me-1"></i>{{ $retur->order_id }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success-subtle rounded me-2">
                                                <i class="ti ti-package text-success"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $retur->product->name ?? '-' }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning-subtle text-warning">
                                            {{ $retur->quantity }} pcs
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-start">
                                            <div class="avatar-sm bg-danger-subtle rounded me-2 flex-shrink-0">
                                                <i class="ti ti-alert-circle text-danger"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted">{{ Str::limit($retur->reason, 50) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge 
                                            @if($retur->status == 'pending') bg-warning-subtle text-warning
                                            @elseif($retur->status == 'approved') bg-success-subtle text-success
                                            @elseif($retur->status == 'rejected') bg-danger-subtle text-danger
                                            @else bg-secondary-subtle text-secondary
                                            @endif
                                        ">
                                            @if($retur->status == 'pending')
                                                <i class="ti ti-clock me-1"></i>
                                            @elseif($retur->status == 'approved')
                                                <i class="ti ti-check me-1"></i>
                                            @elseif($retur->status == 'rejected')
                                                <i class="ti ti-x me-1"></i>
                                            @endif
                                            {{ ucfirst($retur->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $retur->note ?? '-' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-package-export text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="mt-2 text-muted">Belum ada retur barang</h6>
                                            <p class="text-muted mb-0">Data retur barang akan muncul di sini</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($returns, 'hasPages') && $returns->hasPages())
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-center">
                                {{ $returns->links('pagination::bootstrap-4') }}
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