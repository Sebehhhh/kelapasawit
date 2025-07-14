@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Hapus notifikasi sukses/error di sini, cukup di layout utama --}}
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Order Masuk</h4>
        <div>
            <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#printModal">
                <i class="ti ti-file-download"></i> Cetak Laporan
            </a>
        </div>
    </div>
    <!-- FILTER ORDER -->
    <div class="card mb-3">
        <div class="card-body pb-2">
            <form class="row g-2 align-items-end" method="GET" action="">
                <div class="col-md-3">
                    <label for="filter_start_date" class="form-label mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="filter_start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="filter_end_date" class="form-label mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="filter_end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="filter_status" class="form-label mb-1">Status</label>
                    <select name="status" id="filter_status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" @if(isset($selectedStatus) && $selectedStatus=='pending') selected @endif>Pending</option>
                        <option value="paid" @if(isset($selectedStatus) && $selectedStatus=='paid') selected @endif>Paid</option>
                        <option value="shipped" @if(isset($selectedStatus) && $selectedStatus=='shipped') selected @endif>Shipped</option>
                        <option value="cancelled" @if(isset($selectedStatus) && $selectedStatus=='cancelled') selected @endif>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filter_user" class="form-label mb-1">Nama User</label>
                    <input type="text" name="user_name" id="filter_user" class="form-control" placeholder="Cari user..." value="{{ $searchUser ?? '' }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-success mt-3">Filter</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td>{{ $order->order_date ? $order->order_date->format('d M Y') : '-' }}</td>
                            <td>Rp{{ number_format($order->total_amount,0,',','.') }}</td>
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
                            <td>
                                @if($order->payment)
                                    <span class="badge 
                                        @if($order->payment->status == 'pending') bg-warning
                                        @elseif($order->payment->status == 'accepted') bg-success
                                        @elseif($order->payment->status == 'rejected') bg-danger
                                        @else bg-secondary
                                        @endif
                                    ">
                                        {{ ucfirst($order->payment->status) }}
                                    </span>
                                    @if($order->payment->proof_image)
                                        <a href="{{ asset('storage/' . $order->payment->proof_image) }}" target="_blank">Bukti</a>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info btnDetailOrder" data-order='@json($order)'>Detail</button>
                                @if($order->payment && $order->payment->status == 'pending')
                                    <form method="POST" action="{{ route('admin.payments.validate', $order->payment->id) }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui pembayaran ini?')">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.payments.validate', $order->payment->id) }}" class="d-inline ms-1">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pembayaran ini?')">Tolak</button>
                                    </form>
                                @endif
                                @if($order->status == 'paid')
                                    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="d-inline ms-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="shipped">
                                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Kirim order ini?')">Kirim</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada order.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3 d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
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