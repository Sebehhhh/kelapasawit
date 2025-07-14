@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Order Masuk</h4>
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
                            <td><span class="badge bg-info">{{ ucfirst($order->status) }}</span></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info btnDetailOrder" data-order='@json($order)'>Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada order.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $orders->links() }}</div>
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

<!-- SweetAlert2 & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
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
        if(order.invoice) {
            html += `<b>Invoice:</b> <a href='/storage/invoices/${order.invoice.file_path}' target='_blank'>${order.invoice.file_path}</a><br>`;
        }
        if(order.payment) {
            html += `<b>Pembayaran:</b> ${order.payment.amount_paid ? 'Rp'+order.payment.amount_paid.toLocaleString('id-ID') : '-'} (${order.payment.status ?? '-'})<br>`;
            if(order.payment.proof_image) html += `<img src='/storage/payments/${order.payment.proof_image}' width='80'><br>`;
        }
        if(order.shipping) {
            html += `<b>Pengiriman:</b> ${order.shipping.address ?? '-'}, ${order.shipping.city ?? ''}, ${order.shipping.province ?? ''} (${order.shipping.shipping_status ?? '-'})<br>`;
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