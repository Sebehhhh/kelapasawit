@extends('layouts.app')
@section('title', 'Pesanan Saya')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-0">Pesanan Saya</h4>
        <span class="text-muted">Riwayat dan status pesanan Anda.</span>
    </div>
</div>

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal Order</th>
                        <th>Status</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th class="text-center" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php
                    $detail = $order->details->first(); // Asumsi satu produk per order (bisa di-loop kalau multi)
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->order_date->format('d M Y H:i') }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                    $order->status == 'pending' ? 'warning' : (
                                    $order->status == 'paid' ? 'success' : (
                                    $order->status == 'shipped' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            @if($detail)
                            <strong>{{ $detail->product->name }}</strong>
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @if($detail)
                            {{ $detail->quantity }}
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-info btn-order-detail" data-order='@json($order)'
                                data-detail='@json($detail)' data-product='@json($detail ? $detail->product : null)'>
                                <i class="ti ti-search"></i>
                            </a>
                            @if($order->status === 'pending')
                            <button type="button" class="btn btn-sm btn-success btn-pay-order"
                                data-order-id="{{ $order->id }}" data-total="{{ $order->total_amount }}">
                                <i class="ti ti-credit-card"></i> Bayar
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada pesanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>

{{-- Modal Detail Pesanan --}}
<div class="modal fade" id="modalOrderDetail" tabindex="-1" aria-labelledby="modalOrderDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalOrderDetailLabel">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row" id="orderDetailBody">
                <!-- Isi via JS -->
            </div>
        </div>
    </div>
</div>

{{-- Modal Pembayaran --}}
<div class="modal fade" id="modalPayOrder" tabindex="-1" aria-labelledby="modalPayOrderLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formPayOrder" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <input type="hidden" name="order_id" id="payOrderId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalPayOrderLabel">Upload Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nominal Transfer (Rp)</label>
                        <input type="number" min="1000" class="form-control" id="payAmount" name="amount_paid" required
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Pilih Metode...</option>
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bukti Pembayaran <span class="text-danger">*</span></label>
                        <input type="file" accept="image/*,application/pdf" class="form-control" name="proof_image"
                            required>
                        <div class="form-text">Format JPG/PNG/PDF, max 2MB.</div>
                    </div>
                    <div class="mb-2 fw-bold">Total Tagihan: Rp <span id="payOrderTotal"></span></div>
                    <div id="payOrderAlert" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Kirim Pembayaran</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-order-detail').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        let order = JSON.parse(this.dataset.order);
        let detail = this.dataset.detail ? JSON.parse(this.dataset.detail) : null;
        let product = this.dataset.product ? JSON.parse(this.dataset.product) : null;
        let html = `
            <div class="col-md-5 text-center mb-3">
                <img src="${product && product.image ? '/storage/products/' + product.image : '/assets/images/no-image.png'}" class="img-fluid" style="max-height:180px;object-fit:contain;">
            </div>
            <div class="col-md-7">
                <h5 class="fw-bold mb-1">${product ? product.name : '-'}</h5>
                <div class="mb-1 text-muted">${product && product.category ? product.category.name : ''}</div>
                <div class="mb-3">Jumlah: <strong>${detail ? detail.quantity : '-'}</strong></div>
                <div class="mb-2">Harga: <strong>Rp ${detail ? Number(detail.price).toLocaleString('id-ID') : '-'}</strong></div>
                <div class="mb-2">Total: <strong>Rp ${order.total_amount ? Number(order.total_amount).toLocaleString('id-ID') : '-'}</strong></div>
                <div class="mb-2">Status: <span class="badge bg-${order.status == 'pending' ? 'warning' : (order.status == 'paid' ? 'success' : (order.status == 'shipped' ? 'info' : 'secondary'))}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span></div>
                <div class="mb-2">Tanggal Order: ${order.order_date ? (new Date(order.order_date)).toLocaleString('id-ID') : '-'}</div>
            </div>
        `;
        document.getElementById('orderDetailBody').innerHTML = html;
        new bootstrap.Modal(document.getElementById('modalOrderDetail')).show();
    });
});
</script>

<script>
    document.querySelectorAll('.btn-pay-order').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('payOrderId').value = btn.dataset.orderId;
            document.getElementById('payOrderTotal').innerText = Number(btn.dataset.total).toLocaleString('id-ID');
            document.getElementById('payAmount').value = btn.dataset.total;
            document.getElementById('payOrderAlert').classList.add('d-none');
            new bootstrap.Modal(document.getElementById('modalPayOrder')).show();
        });
    });
    
    document.getElementById('formPayOrder').onsubmit = function(e){
    e.preventDefault();
    let btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    let formData = new FormData(this);

    fetch('{{ route("customer.payments.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData,
        credentials: 'same-origin'
    })
    .then(async response => {
        // Debug: status code dan response
        console.log('Status:', response.status);
        let contentType = response.headers.get('content-type');
        let data;
        if (contentType && contentType.includes('application/json')) {
            data = await response.json();
        } else {
            data = await response.text();
        }
        console.log('Data:', data);

        btn.disabled = false;
        if(response.status === 200 && data.success){
            bootstrap.Modal.getInstance(document.getElementById('modalPayOrder')).hide();
            Swal.fire('Berhasil!', data.message, 'success').then(() => {
                window.location.reload();
            });
        } else {
            showPayOrderError(data.message || data || 'Gagal mengirim pembayaran!');
        }
    })
    .catch(error => {
        btn.disabled = false;
        console.error('Fetch error:', error);
        showPayOrderError('Gagal terhubung ke server!');
    });
};
    
    function showPayOrderError(msg){
        let alert = document.getElementById('payOrderAlert');
        alert.innerText = msg;
        alert.classList.remove('d-none');
    }
</script>
@endsection