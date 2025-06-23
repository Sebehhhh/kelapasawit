@extends('layouts.app')
@section('title', 'Kelola Produk Bibit Sawit')
@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold mb-0">Daftar Produk</h4>
        <button type="button" class="btn btn-primary" id="btnAddProduct">
            <i class="ti ti-plus"></i> Tambah Produk
        </button>
    </div>
</div>

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kategori</th>
                        <th>Nama Produk</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                        <th class="text-center" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description ?? '-' }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            @if($product->image)
                            <img src="{{ asset('storage/products/' . $product->image) }}" alt="Foto" width="60">
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-info btnEditProduct"
                                data-id="{{ $product->id }}" data-category="{{ $product->category_id }}"
                                data-name="{{ $product->name }}" data-description="{{ $product->description }}"
                                data-price="{{ $product->price }}" data-stock="{{ $product->stock }}"
                                data-image="{{ $product->image }}">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btnDeleteProduct"
                                data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Produk -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="productForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="product_id" name="product_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="productModalLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="price" name="price" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stock" name="stock" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Produk</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div id="previewImage" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 & jQuery CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
  // TOMBOL TAMBAH
  $('#btnAddProduct').click(function() {
    $('#productForm')[0].reset();
    $('#product_id').val('');
    $('#productModalLabel').text('Tambah Produk');
    $('#previewImage').html('');
    $('#productModal').modal('show');
  });

  // TOMBOL EDIT
  $('.btnEditProduct').click(function() {
    let btn = $(this);
    $('#product_id').val(btn.data('id'));
    $('#category_id').val(btn.data('category'));
    $('#name').val(btn.data('name'));
    $('#description').val(btn.data('description'));
    $('#price').val(btn.data('price'));
    $('#stock').val(btn.data('stock'));
    $('#productModalLabel').text('Edit Produk');
    // Preview gambar
    let img = btn.data('image');
    if (img) {
      $('#previewImage').html('<img src="/storage/products/' + img + '" alt="Foto" width="100">');
    } else {
      $('#previewImage').html('');
    }
    $('#productModal').modal('show');
  });

  // Preview gambar baru
  $('#image').change(function(){
    let reader = new FileReader();
    reader.onload = function(e){
      $('#previewImage').html('<img src="' + e.target.result + '" width="100">');
    }
    if(this.files[0]) reader.readAsDataURL(this.files[0]);
  });

  // SUBMIT FORM (TAMBAH/EDIT)
  $('#productForm').submit(function(e) {
    e.preventDefault();
    let id = $('#product_id').val();
    let url = id ? '{{ route("admin.products.update", ":id") }}'.replace(':id', id) : '{{ route("admin.products.store") }}';
    let method = id ? 'POST' : 'POST';
    let formData = new FormData(this);
    if (id) formData.append('_method', 'PUT');

    $.ajax({
      url: url,
      type: method,
      data: formData,
      contentType: false,
      processData: false,
      success: function(res) {
        $('#productModal').modal('hide');
        Swal.fire('Sukses!', res.message ?? 'Data berhasil disimpan.', 'success').then(() => {
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

  // HAPUS
  $('.btnDeleteProduct').click(function() {
    let id = $(this).data('id');
    let name = $(this).data('name');
    Swal.fire({
      title: `Hapus produk?`,
      text: `Produk "${name}" akan dihapus permanen.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '{{ url("admin/products") }}/'+id,
          type: 'POST',
          data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
          success: function(res) {
            Swal.fire('Sukses!', res.message ?? 'Data berhasil dihapus.', 'success').then(() => {
              window.location.reload();
            });
          },
          error: function(xhr) {
            let msg = 'Gagal menghapus.';
            if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            Swal.fire('Error', msg, 'error');
          }
        });
      }
    });
  });
});
</script>
@endsection