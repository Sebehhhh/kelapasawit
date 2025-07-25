@extends('layouts.app')
@section('title', 'Pesanan Saya')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
@endpush
@section('content')
{{-- Hero Header Section --}}
<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); min-height: 150px;">
            <div class="card-body d-flex align-items-center text-white">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar-circle bg-white bg-opacity-20 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%;">
                            <i class="ti ti-shopping-bag fs-2 text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">Pesanan Saya</h3>
                            <p class="mb-0 opacity-90">Kelola dan pantau status pesanan Anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Orders Cards Section --}}
<div class="row g-4">
    @forelse($orders as $order)
    @php
    $detail = $order->details->first();
    @endphp
    <div class="col-12">
        <div class="card border-0 shadow-sm order-card">
            <div class="card-body">
                <div class="row align-items-center">
                    {{-- Product Image --}}
                    <div class="col-md-2 col-3 text-center">
                        @if($detail && $detail->product)
                        <img src="{{ $detail->product->image ? asset('storage/products/'.$detail->product->image) : asset('assets/images/no-image.png') }}" 
                             alt="{{ $detail->product->name }}" 
                             class="img-fluid rounded" 
                             style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="ti ti-package text-muted fs-2"></i>
                        </div>
                        @endif
                    </div>
                    
                    {{-- Order Info --}}
                    <div class="col-md-6 col-9">
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="fw-bold mb-0 me-2">
                                @if($detail && $detail->product)
                                    {{ $detail->product->name }}
                                @else
                                    Produk tidak tersedia
                                @endif
                            </h5>
                            <span class="badge bg-{{ 
                                $order->status == 'pending' ? 'warning' : (
                                $order->status == 'paid' ? 'info' : (
                                $order->status == 'shipped' ? 'success' : (
                                $order->status == 'cancelled' ? 'danger' : 'secondary'))) }}">
                                @if($order->status == 'cancelled')
                                    Dibatalkan
                                @elseif($order->status == 'pending')
                                    Menunggu Pembayaran
                                @elseif($order->status == 'paid')
                                    Sudah Dibayar
                                @elseif($order->status == 'shipped')
                                    Selesai
                                @else
                                    {{ ucfirst($order->status) }}
                                @endif
                            </span>
                        </div>
                        
                        <div class="row text-muted small mb-2">
                            <div class="col-6">
                                <i class="ti ti-calendar me-1"></i>
                                {{ $order->order_date->format('d M Y, H:i') }}
                            </div>
                            <div class="col-6">
                                <i class="ti ti-package me-1"></i>
                                Qty: {{ $detail ? $detail->quantity : 0 }}
                            </div>
                        </div>
                        
                        @if($order->status == 'cancelled' && $order->payment && $order->payment->status == 'rejected')
                        <div class="alert alert-danger py-2 mb-0">
                            <i class="ti ti-alert-circle me-1"></i>
                            <small>Pembayaran ditolak</small>
                        </div>
                        @endif
                    </div>
                    
                    {{-- Price & Actions --}}
                    <div class="col-md-4 col-12 text-md-end mt-3 mt-md-0">
                        <div class="mb-3">
                            <div class="text-muted small">Total Pembayaran</div>
                            <div class="fw-bold text-primary fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            {{-- Detail Button --}}
                            <button class="btn btn-sm btn-outline-info btn-order-detail" 
                                    data-order='@json($order)'
                                    data-detail='@json($detail)' 
                                    data-product='@json($detail ? $detail->product : null)'>
                                <i class="ti ti-eye me-1"></i>Detail
                            </button>
                            
                            {{-- Payment Button --}}
                            @if($order->status === 'pending')
                            <button type="button" class="btn btn-sm btn-success btn-pay-order"
                                    data-order-id="{{ $order->id }}" 
                                    data-total="{{ $order->total_amount }}">
                                <i class="ti ti-credit-card me-1"></i>Bayar Sekarang
                            </button>
                            @endif
                            
                            {{-- Testimonial Button --}}
                            @if($order->status === 'shipped' && $detail && $detail->product && !$detail->testimonial)
                            <button type="button" class="btn btn-sm btn-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalGiveTestimonial{{ $detail->id }}">
                                <i class="ti ti-star me-1"></i>Beri Testimoni
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Testimonial Modal per order --}}
    @if($order->status === 'shipped' && $detail && $detail->product && !$detail->testimonial)
    <div class="modal fade" id="modalGiveTestimonial{{ $detail->id }}" tabindex="-1" aria-labelledby="modalGiveTestimonialLabel{{ $detail->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formTestimoni{{ $detail->id }}" autocomplete="off" method="POST" action="{{ route('customer.testimonials.store') }}">
                @csrf
                <input type="hidden" name="order_detail_id" value="{{ $detail->id }}">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title fw-bold" id="modalGiveTestimonialLabel{{ $detail->id }}">
                            <i class="ti ti-star me-2"></i>Beri Testimoni
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <img src="{{ $detail->product->image ? asset('storage/products/'.$detail->product->image) : asset('assets/images/no-image.png') }}" 
                                 alt="{{ $detail->product->name }}" 
                                 class="img-fluid rounded" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Produk</label>
                            <input type="text" class="form-control" value="{{ $detail->product->name }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rating</label>
                            <select name="rating" class="form-select" required>
                                <option value="">Pilih Rating</option>
                                @for($i=1;$i<=5;$i++)
                                    <option value="{{ $i }}">{{ $i }} Bintang {!! str_repeat('⭐', $i) !!}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Komentar</label>
                            <textarea name="message" class="form-control" rows="4" maxlength="1000" placeholder="Bagikan pengalaman Anda dengan produk ini..." required></textarea>
                            <div class="form-text">Maksimal 1000 karakter</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="ti ti-send me-1"></i>Kirim Testimoni
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="ti ti-shopping-bag-off fs-1 text-muted mb-3"></i>
                <h5 class="text-muted mb-2">Belum Ada Pesanan</h5>
                <p class="text-muted mb-3">Anda belum memiliki pesanan apapun</p>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary">
                    <i class="ti ti-shopping-cart me-1"></i>Mulai Berbelanja
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($orders->hasPages())
<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endif

{{-- Legacy table structure removed --}}
<div class="d-none">
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
                    </table>
                </div>
            </div>
        </div>
    </div>

{{-- Modal Detail Pesanan --}}
<div class="modal fade" id="modalOrderDetail" tabindex="-1" aria-labelledby="modalOrderDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold" id="modalOrderDetailLabel">
                    <i class="ti ti-file-text me-2"></i>Detail Pesanan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailBody">
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
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="modalPayOrderLabel">
                        <i class="ti ti-credit-card me-2"></i>Upload Bukti Pembayaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Informasi Pembayaran:</strong><br>
                        Silakan pilih metode pembayaran di bawah ini dan transfer sesuai instruksi
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Total Tagihan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" min="1000" class="form-control fw-bold" id="payAmount" name="amount_paid" required readonly>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select" id="paymentMethodSelect" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            @if(isset($paymentMethods['rekening']))
                            <optgroup label="🏦 Transfer Bank">
                                @foreach($paymentMethods['rekening'] as $method)
                                <option value="{{ $method->id }}" data-type="rekening" data-name="{{ $method->name }}" data-number="{{ $method->account_number }}" data-owner="{{ $method->account_name }}" data-instructions="{{ $method->instructions }}">
                                    {{ $method->name }} - {{ $method->account_number }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endif
                            @if(isset($paymentMethods['e-wallet']))
                            <optgroup label="💳 E-Wallet">
                                @foreach($paymentMethods['e-wallet'] as $method)
                                <option value="{{ $method->id }}" data-type="e-wallet" data-name="{{ $method->name }}" data-number="{{ $method->account_number }}" data-owner="{{ $method->account_name }}" data-instructions="{{ $method->instructions }}">
                                    {{ $method->name }} - {{ $method->account_number }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endif
                        </select>
                    </div>
                    
                    <div id="paymentDetails" class="mb-3" style="display: none;">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="ti ti-credit-card me-2"></i>Detail Pembayaran</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Metode:</strong> <span id="selectedMethodName"></span></p>
                                        <p class="mb-1"><strong>Nomor:</strong> <span id="selectedMethodNumber" class="text-primary fw-bold"></span></p>
                                        <p class="mb-0"><strong>A.n:</strong> <span id="selectedMethodOwner"></span></p>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="alert alert-warning mb-0">
                                    <small><i class="ti ti-info-circle me-1"></i><span id="selectedMethodInstructions"></span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bukti Pembayaran <span class="text-danger">*</span></label>
                        <input type="file" accept="image/*,application/pdf" class="form-control" name="proof_image" required>
                        <div class="form-text">
                            <i class="ti ti-upload me-1"></i>
                            Format yang diterima: JPG, PNG, PDF (Maksimal 2MB)
                        </div>
                    </div>
                    
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">Total yang harus dibayar:</span>
                                <span class="fw-bold text-success fs-5">Rp <span id="payOrderTotal"></span></span>
                            </div>
                        </div>
                    </div>
                    
                    <div id="payOrderAlert" class="alert alert-danger d-none mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-send me-1"></i>Kirim Pembayaran
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Testimoni --}}
<div class="modal fade" id="modalGiveTestimonial" tabindex="-1" aria-labelledby="modalGiveTestimonialLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formTestimoni" autocomplete="off">
            @csrf
            <input type="hidden" name="order_detail_id" id="testiOrderDetailId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalGiveTestimonialLabel">Beri Testimoni</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Produk</label>
                        <input type="text" class="form-control" id="testiProductName" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div id="testiRating" class="mb-1">
                            @for($i=1;$i<=5;$i++)
                                <i class="ti ti-star rating-star" data-value="{{ $i }}" style="font-size:1.5rem;cursor:pointer;color:#ccc;"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="testiRatingValue" value="5">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Komentar</label>
                        <textarea name="message" class="form-control" id="testiMessage" rows="3" maxlength="1000" required></textarea>
                    </div>
                    <div id="testiAlert" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Kirim Testimoni</button>
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
            <div class="row">
                <div class="col-md-4 text-center mb-3">
                    <img src="${product && product.image ? '/storage/products/' + product.image : '/assets/images/no-image.png'}" 
                         class="img-fluid rounded shadow-sm" 
                         style="max-height:200px;object-fit:cover;border: 2px solid #e9ecef;">
                </div>
                <div class="col-md-8">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h4 class="fw-bold mb-3 text-primary">${product ? product.name : 'Produk tidak tersedia'}</h4>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Kategori</small>
                                    <div class="fw-semibold">${product && product.category ? product.category.name : 'Tidak ada kategori'}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Jumlah</small>
                                    <div class="fw-semibold">${detail ? detail.quantity : 0} pcs</div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Harga Satuan</small>
                                    <div class="fw-bold text-success">Rp ${detail ? Number(detail.price).toLocaleString('id-ID') : '0'}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Total Harga</small>
                                    <div class="fw-bold text-primary fs-5">Rp ${order.total_amount ? Number(order.total_amount).toLocaleString('id-ID') : '0'}</div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Status Pesanan</small>
                                    <div>
                                        <span class="badge bg-${order.status == 'pending' ? 'warning' : (order.status == 'paid' ? 'info' : (order.status == 'shipped' ? 'success' : (order.status == 'cancelled' ? 'danger' : 'secondary')))} fs-6">
                                            ${order.status == 'cancelled' ? 'Dibatalkan' : 
                                              order.status == 'pending' ? 'Menunggu Pembayaran' :
                                              order.status == 'paid' ? 'Sudah Dibayar' :
                                              order.status == 'shipped' ? 'Selesai' :
                                              order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Tanggal Order</small>
                                    <div class="fw-semibold">${order.order_date ? (new Date(order.order_date)).toLocaleDateString('id-ID', {
                                        weekday: 'long', 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }) : 'Tidak diketahui'}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            // Reset payment method selection
            document.getElementById('paymentMethodSelect').value = '';
            document.getElementById('paymentDetails').style.display = 'none';
            new bootstrap.Modal(document.getElementById('modalPayOrder')).show();
        });
    });

    // Handle payment method selection
    document.getElementById('paymentMethodSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const paymentDetails = document.getElementById('paymentDetails');
        
        if (selectedOption.value) {
            // Show payment details
            document.getElementById('selectedMethodName').textContent = selectedOption.dataset.name;
            document.getElementById('selectedMethodNumber').textContent = selectedOption.dataset.number;
            document.getElementById('selectedMethodOwner').textContent = selectedOption.dataset.owner;
            document.getElementById('selectedMethodInstructions').textContent = selectedOption.dataset.instructions;
            paymentDetails.style.display = 'block';
        } else {
            paymentDetails.style.display = 'none';
        }
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
@push('styles')
<style>
.order-card {
    transition: all 0.3s ease;
    border-radius: 12px !important;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
}

.avatar-circle {
    backdrop-filter: blur(10px);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.order-card {
    animation: fadeInUp 0.6s ease forwards;
}

.badge {
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
}

@media (max-width: 768px) {
    .order-card .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Add stagger animation to order cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.order-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});

// Modal Testimoni
let testiModal = new bootstrap.Modal(document.getElementById('modalGiveTestimonial'));
document.querySelectorAll('.btn-give-testimonial').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('testiOrderDetailId').value = this.dataset.order_detail_id;
        document.getElementById('testiProductName').value = this.dataset.product_name || '-';
        document.getElementById('testiMessage').value = '';
        setRating(5);
        document.getElementById('testiAlert').classList.add('d-none');
        testiModal.show();
    });
});

// Rating bintang
function setRating(val) {
    document.getElementById('testiRatingValue').value = val;
    document.querySelectorAll('#testiRating .rating-star').forEach((star, idx) => {
        star.style.color = (idx < val) ? '#ffc107' : '#ccc';
    });
}
document.querySelectorAll('#testiRating .rating-star').forEach(star => {
    star.addEventListener('click', function() {
        setRating(Number(this.dataset.value));
    });
});

// Submit Testimoni
const formTestimoni = document.getElementById('formTestimoni');
if(formTestimoni) {
    formTestimoni.onsubmit = function(e) {
        e.preventDefault();
        let btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        let formData = new FormData(this);
        fetch("{{ route('customer.testimonials.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData,
            credentials: 'same-origin'
        })
        .then(async response => {
            btn.disabled = false;
            let data = await response.text();
            try { data = JSON.parse(data); } catch {}
            if(response.ok && (!data.error)){
                testiModal.hide();
                Swal.fire('Berhasil!', data.message || 'Testimoni berhasil dikirim!', 'success').then(() => {
                    window.location.reload();
                });
            } else {
                showTestiError(data.message || data.error || 'Gagal mengirim testimoni!');
            }
        })
        .catch(error => {
            btn.disabled = false;
            showTestiError('Gagal terhubung ke server!');
        });
    };
}

function showTestiError(msg){
    let alert = document.getElementById('testiAlert');
    if(alert) {
        alert.innerText = msg;
        alert.classList.remove('d-none');
    }
}
</script>
@endpush
@endsection