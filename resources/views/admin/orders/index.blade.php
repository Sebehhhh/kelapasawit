@extends('layouts.app')
@section('title', 'Kelola Order Masuk')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="ti ti-shopping-cart me-2"></i>
                                Kelola Order Masuk
                            </h4>
                            <p class="mb-0 opacity-75">Manajemen pesanan dari pelanggan</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="ti ti-file-download me-1"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#printModal">
                                        <i class="ti ti-file-text me-2"></i>Cetak Laporan
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.orders.strukKeluar') }}">
                                        <i class="ti ti-file-export me-2"></i>Struk Keluar
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.orders.strukMasuk') }}">
                                        <i class="ti ti-file-import me-2"></i>Struk Masuk
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.orders.salesReport') }}">
                                        <i class="ti ti-chart-bar me-2"></i>Penjualan Harian
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="ti ti-filter me-2 text-primary"></i>Filter & Pencarian
                    </h6>
                </div>
                <div class="card-body">
                    <form class="row g-3 align-items-end" method="GET" action="">
                        <div class="col-md-3">
                            <label for="filter_start_date" class="form-label mb-2 fw-semibold">
                                <i class="ti ti-calendar me-1 text-primary"></i> Tanggal Mulai
                            </label>
                            <input type="date" name="start_date" id="filter_start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="filter_end_date" class="form-label mb-2 fw-semibold">
                                <i class="ti ti-calendar me-1 text-primary"></i> Tanggal Akhir
                            </label>
                            <input type="date" name="end_date" id="filter_end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="filter_status" class="form-label mb-2 fw-semibold">
                                <i class="ti ti-info-circle me-1 text-primary"></i> Status
                            </label>
                            <select name="status" id="filter_status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending" @if(isset($selectedStatus) && $selectedStatus=='pending') selected @endif>Pending</option>
                                <option value="paid" @if(isset($selectedStatus) && $selectedStatus=='paid') selected @endif>Paid</option>
                                <option value="shipped" @if(isset($selectedStatus) && $selectedStatus=='shipped') selected @endif>Shipped</option>
                                <option value="cancelled" @if(isset($selectedStatus) && $selectedStatus=='cancelled') selected @endif>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter_user" class="form-label mb-2 fw-semibold">
                                <i class="ti ti-user me-1 text-primary"></i> Nama User
                            </label>
                            <input type="text" name="user_name" id="filter_user" class="form-control" placeholder="Cari user..." value="{{ $searchUser ?? '' }}">
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-refresh me-1"></i> Reset
                            </a>
                        </div>
                    </form>
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
                        <i class="ti ti-list me-2 text-primary"></i>Daftar Order
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <tr>
                                    <th class="text-center fw-bold" style="width: 60px">#</th>
                                    <th class="fw-bold"><i class="ti ti-user me-1"></i>User</th>
                                    <th class="fw-bold"><i class="ti ti-calendar me-1"></i>Tanggal</th>
                                    <th class="fw-bold text-end"><i class="ti ti-currency-dollar me-1"></i>Total</th>
                                    <th class="fw-bold text-center"><i class="ti ti-info-circle me-1"></i>Status</th>
                                    <th class="fw-bold text-center"><i class="ti ti-credit-card me-1"></i>Pembayaran</th>
                                    <th class="fw-bold text-center" style="width:120px;"><i class="ti ti-settings me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fw-semibold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info-subtle rounded me-2">
                                                <i class="ti ti-user text-info"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $order->user->name ?? '-' }}</h6>
                                                <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="ti ti-calendar me-1"></i>
                                            {{ $order->order_date ? $order->order_date->format('d/m/Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-success">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge 
                                            @if($order->status == 'pending') bg-warning-subtle text-warning
                                            @elseif($order->status == 'paid') bg-success-subtle text-success
                                            @elseif($order->status == 'shipped') bg-primary-subtle text-primary
                                            @elseif($order->status == 'cancelled') bg-danger-subtle text-danger
                                            @else bg-secondary-subtle text-secondary
                                            @endif
                                        ">
                                            @if($order->status == 'pending')
                                                <i class="ti ti-clock me-1"></i>
                                            @elseif($order->status == 'paid')
                                                <i class="ti ti-check me-1"></i>
                                            @elseif($order->status == 'shipped')
                                                <i class="ti ti-truck me-1"></i>
                                            @elseif($order->status == 'cancelled')
                                                <i class="ti ti-x me-1"></i>
                                            @endif
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($order->payment)
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <span class="badge 
                                                    @if($order->payment->status == 'pending') bg-warning-subtle text-warning
                                                    @elseif($order->payment->status == 'accepted') bg-success-subtle text-success
                                                    @elseif($order->payment->status == 'rejected') bg-danger-subtle text-danger
                                                    @else bg-secondary-subtle text-secondary
                                                    @endif
                                                ">
                                                    {{ ucfirst($order->payment->status) }}
                                                </span>
                                                @if($order->payment->proof_image)
                                                    <a href="{{ asset('storage/' . $order->payment->proof_image) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class="ti ti-photo"></i> Bukti
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-info btn-sm btnDetailOrder" data-bs-toggle="tooltip" title="Detail" data-order='@json($order)'>
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            @if($order->payment && $order->payment->status == 'pending')
                                                <form method="POST" action="{{ route('admin.payments.validate', $order->payment->id) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="accept">
                                                    <button type="submit" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Setujui" onclick="return confirm('Setujui pembayaran ini?')">
                                                        <i class="ti ti-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.payments.validate', $order->payment->id) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Tolak" onclick="return confirm('Tolak pembayaran ini?')">
                                                        <i class="ti ti-x"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($order->status == 'paid')
                                                <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="shipped">
                                                    <button type="submit" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Kirim" onclick="return confirm('Kirim order ini?')">
                                                        <i class="ti ti-truck"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-shopping-cart text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="mt-2 text-muted">Belum ada order</h6>
                                            <p class="text-muted mb-0">Order dari pelanggan akan muncul di sini</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($orders, 'hasPages') && $orders->hasPages())
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-center">
                                {{ $orders->links('pagination::bootstrap-4') }}
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
</div>

<!-- Modal Detail Order -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="orderModalLabel">Detail Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div id="orderDetailContent"></div>
                <div class="mt-3">
                    <form id="orderStatusForm">
                        @csrf
                        <input type="hidden" id="order_id" name="order_id">
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Ubah Status Order</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="pending">pending</option>
                                    <option value="paid">paid</option>
                                    <option value="shipped">shipped</option>
                                    <option value="cancelled">cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Simpan Status</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cetak Laporan -->
<div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printModalLabel">Cetak Laporan Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="{{ route('admin.orders.printReport') }}" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle"></i>
                        <strong>Tips:</strong> Pastikan tanggal yang dipilih sesuai dengan data order yang tersedia. 
                        Data order saat ini tersedia untuk tanggal <strong>{{ date('d/m/Y') }}</strong>.
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="status" class="form-label">Status Order</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="shipped">Shipped</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-file-download"></i> Cetak PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // Set tanggal default untuk modal cetak (hari ini)
    let today = new Date();
    
    $('#start_date').val(today.toISOString().split('T')[0]);
    $('#end_date').val(today.toISOString().split('T')[0]);
    
    // TOMBOL DETAIL
    $('.btnDetailOrder').click(function() {
        let order = $(this).data('order');
        let html = `<div class='row mb-2'>
            <div class='col-md-6'><b>User:</b> ${order.user?.name ?? '-'}<br><b>Email:</b> ${order.user?.email ?? '-'}</div>
            <div class='col-md-6'><b>Tanggal:</b> ${order.order_date ?? '-'}<br><b>Status:</b> <span class='badge bg-info'>${order.status}</span></div>
        </div>`;
        html += `<b>Item Pesanan:</b><table class='table table-sm'><thead><tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead><tbody>`;
        order.details.forEach(function(item) {
            html += `<tr><td>${item.product?.name ?? '-'}</td><td>${item.quantity}</td><td>Rp${item.price.toLocaleString('id-ID')}</td><td>Rp${(item.price*item.quantity).toLocaleString('id-ID')}</td></tr>`;
        });
        html += `</tbody></table>`;
        html += `<b>Total:</b> Rp${order.total_amount.toLocaleString('id-ID')}<br>`;
        if(order.payment) {
            html += `<b>Pembayaran:</b> ${order.payment.amount_paid ? 'Rp'+order.payment.amount_paid.toLocaleString('id-ID') : '-'} (${order.payment.status ?? '-'})<br>`;
            if(order.payment.proof_image) html += `<img src='/storage/payments/${order.payment.proof_image}' width='80'><br>`;
        }
        $('#orderDetailContent').html(html);
        $('#order_id').val(order.id);
        $('#status').val(order.status);
        
        $('#orderModal').modal('show');
    });

    // SUBMIT STATUS
    $('#orderStatusForm').submit(function(e) {
        e.preventDefault();
        let id = $('#order_id').val();
        let url = '{{ route("admin.orders.update", ":id") }}'.replace(':id', id);
        let formData = $(this).serialize() + '&_method=PUT';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(res) {
                Swal.fire('Sukses!', res.message ?? 'Status order berhasil diupdate.', 'success').then(() => {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                let msg = 'Terjadi error. Pastikan data valid!';
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire('Gagal!', msg, 'error');
            }
        });
    });
});
</script>
@endsection