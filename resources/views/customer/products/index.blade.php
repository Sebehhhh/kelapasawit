@extends('layouts.app')
@section('title', 'Katalog Bibit Sawit')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-0">Katalog Bibit Kelapa Sawit</h4>
        <span class="text-muted">Temukan produk terbaik sesuai kebutuhan Anda!</span>
    </div>
</div>

<div class="row">
    @forelse($products as $product)
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow h-100">
            <div class="card-img-top text-center pt-4">
                @if($product->image)
                <img src="{{ asset('storage/products/'.$product->image) }}" alt="{{ $product->name }}"
                    style="max-height:160px;max-width:100%;object-fit:contain;">
                @else
                <img src="{{ asset('assets/images/no-image.png') }}" alt="No Image" style="max-height:160px;">
                @endif
            </div>
            <div class="card-body d-flex flex-column">
                <h5 class="fw-bold mb-1">{{ $product->name }}</h5>
                <div class="mb-1 text-muted small">{{ $product->category->name ?? '-' }}</div>
                <h4 class="fw-bold text-success mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                <div class="mt-auto">
                    <a href="#" class="btn btn-info w-100 mb-2 btn-show-detail" data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}" data-category="{{ $product->category->name ?? '-' }}"
                        data-description="{{ $product->description ?? '-' }}"
                        data-price="Rp {{ number_format($product->price, 0, ',', '.') }}"
                        data-image="{{ $product->image ? asset('storage/products/'.$product->image) : asset('assets/images/no-image.png') }}">
                        <i class="ti ti-search"></i> Detail Produk
                    </a>
                    <button type="button" class="btn btn-primary w-100 btn-checkout" data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}" data-stock="{{ $product->stock }}"
                        data-price="{{ $product->price }}"
                        data-image="{{ $product->image ? asset('storage/products/'.$product->image) : asset('assets/images/no-image.png') }}">
                        <i class="ti ti-shopping-cart"></i> Pesan Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center text-muted">
        <p>Tidak ada produk tersedia saat ini.</p>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalDetailLabel">Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row">
                <div class="col-md-5 text-center mb-3">
                    <img id="modalImage" src="" alt="Produk" class="img-fluid"
                        style="max-height:220px;object-fit:contain;">
                </div>
                <div class="col-md-7">
                    <h4 class="fw-bold" id="modalName"></h4>
                    <div class="mb-1 text-muted" id="modalCategory"></div>
                    <div class="mb-3" id="modalDescription"></div>
                    <h3 class="fw-bold text-success" id="modalPrice"></h3>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CHECKOUT --}}
<div class="modal fade" id="modalCheckout" tabindex="-1" aria-labelledby="modalCheckoutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formCheckout" autocomplete="off">
            @csrf
            <input type="hidden" name="product_id" id="checkoutProductId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalCheckoutLabel">Checkout Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="checkoutImage" src="" alt="Produk" class="img-fluid"
                            style="max-height:120px;object-fit:contain;">
                    </div>
                    <h5 class="fw-bold mb-1" id="checkoutName"></h5>
                    <div class="mb-2 text-muted">Stok tersedia: <span id="checkoutStock"></span></div>
                    <div class="mb-2 fw-bold text-success">Harga: Rp <span id="checkoutPrice"></span></div>
                    <div class="mb-3">
                        <label for="qty" class="form-label">Jumlah Beli</label>
                        <input type="number" class="form-control" min="1" id="checkoutQty" name="qty" value="1"
                            required>
                    </div>
                    <div class="mb-2">
                        <h5>Total: <span class="fw-bold text-success" id="checkoutTotal">Rp 0</span></h5>
                    </div>
                    <div id="checkoutAlert" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Checkout</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- JS Section --}}
<!-- Bootstrap JS (pastikan ini sudah di-include!) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // MODAL DETAIL
    document.querySelectorAll('.btn-show-detail').forEach(btn => {
      btn.addEventListener('click', function(e){
        e.preventDefault();
        document.getElementById('modalName').innerText = btn.dataset.name;
        document.getElementById('modalCategory').innerText = btn.dataset.category;
        document.getElementById('modalDescription').innerText = btn.dataset.description;
        document.getElementById('modalPrice').innerText = btn.dataset.price;
        document.getElementById('modalImage').src = btn.dataset.image;
        new bootstrap.Modal(document.getElementById('modalDetail')).show();
      });
    });

    // MODAL CHECKOUT
    let checkoutModal = new bootstrap.Modal(document.getElementById('modalCheckout'));
    document.querySelectorAll('.btn-checkout').forEach(btn => {
      btn.addEventListener('click', function(e){
        e.preventDefault();
        let id = btn.dataset.id;
        let name = btn.dataset.name;
        let stock = parseInt(btn.dataset.stock);
        let price = parseInt(btn.dataset.price);
        let img = btn.dataset.image;

        document.getElementById('checkoutProductId').value = id;
        document.getElementById('checkoutName').innerText = name;
        document.getElementById('checkoutStock').innerText = stock;
        document.getElementById('checkoutPrice').innerText = price.toLocaleString('id-ID');
        document.getElementById('checkoutQty').value = 1;
        document.getElementById('checkoutImage').src = img;
        updateCheckoutTotal(price, 1);

        // Reset alert
        document.getElementById('checkoutAlert').classList.add('d-none');

        // Validasi saat input jumlah
        document.getElementById('checkoutQty').oninput = function() {
          let qty = parseInt(this.value) || 1;
          if(qty > stock) {
            this.value = stock;
            qty = stock;
          }
          if(qty < 1) {
            this.value = 1;
            qty = 1;
          }
          updateCheckoutTotal(price, qty);
        };

        checkoutModal.show();
      });
    });

    function updateCheckoutTotal(price, qty) {
      let total = price * qty;
      document.getElementById('checkoutTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    // SUBMIT FORM CHECKOUT
    document.getElementById('formCheckout').onsubmit = function(e){
      e.preventDefault();
      let btn = this.querySelector('button[type="submit"]');
      btn.disabled = true;
      let formData = new FormData(this);
      fetch('{{ route("customer.orders.store") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': formData.get('_token')
        },
        body: formData
      })
      .then(response => response.json())
      .then(res => {
        btn.disabled = false;
        if(res.success){
          checkoutModal.hide();
          // SweetAlert2 notifikasi (jika pakai SweetAlert2)
          Swal.fire('Berhasil!', res.message, 'success').then(() => {
            window.location.reload();
          });
        }else{
          showCheckoutError(res.message || 'Gagal melakukan checkout!');
        }
      })
      .catch(() => {
        btn.disabled = false;
        showCheckoutError('Gagal terhubung ke server!');
      });
    };

    function showCheckoutError(msg){
      let alert = document.getElementById('checkoutAlert');
      alert.innerText = msg;
      alert.classList.remove('d-none');
    }
</script>
@endsection