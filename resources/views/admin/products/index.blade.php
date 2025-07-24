@extends('layouts.app')
@section('title', 'Kelola Produk Bibit Sawit')
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
                                <i class="ti ti-package me-2"></i>
                                Kelola Produk Bibit Sawit
                            </h4>
                            <p class="mb-0 opacity-75">Manajemen produk bibit kelapa sawit</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="ti ti-file-download me-1"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.products.printReport', request()->all()) }}" target="_blank">
                                        <i class="ti ti-file-text me-2"></i>Laporan Produk
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.products.stokReport') }}">
                                        <i class="ti ti-clipboard-data me-2"></i>Laporan Stok
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.products.topProductsReport') }}">
                                        <i class="ti ti-star me-2"></i>Produk Terlaris
                                    </a></li>
                                </ul>
                            </div>
                            <button type="button" class="btn btn-light" id="btnAddProduct">
                                <i class="ti ti-plus me-1"></i> Tambah Produk
                            </button>
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
                            <label for="filter_category" class="form-label mb-2 fw-semibold">
                                <i class="ti ti-category me-1 text-primary"></i> Kategori
                            </label>
                            <select name="category_id" id="filter_category" class="form-select">
                                <option value="">-- Semua Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @if(isset($selectedCategory) && $selectedCategory == $cat->id) selected @endif>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_name" class="form-label mb-2 fw-semibold">
                                <i class="ti ti-search me-1 text-primary"></i> Nama Produk
                            </label>
                            <input type="text" name="name" id="filter_name" class="form-control" placeholder="Cari nama produk..." value="{{ $searchName ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label for="sort_by" class="form-label mb-2 fw-semibold">
                                <i class="ti ti-sort-descending me-1 text-primary"></i> Urutkan
                            </label>
                            <select name="sort_by" id="sort_by" class="form-select">
                                <option value="">Tanggal Terbaru</option>
                                <option value="price" @if(isset($sortBy) && $sortBy=='price') selected @endif>Harga</option>
                                <option value="stock" @if(isset($sortBy) && $sortBy=='stock') selected @endif>Stok</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sort_order" class="form-label mb-2 fw-semibold">
                                <i class="ti ti-arrows-sort me-1 text-primary"></i> Urutan
                            </label>
                            <select name="sort_order" id="sort_order" class="form-select">
                                <option value="desc" @if(isset($sortOrder) && $sortOrder=='desc') selected @endif>Terbesar</option>
                                <option value="asc" @if(isset($sortOrder) && $sortOrder=='asc') selected @endif>Terkecil</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-refresh me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    
    <!-- Data Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="ti ti-list me-2 text-primary"></i>Daftar Produk
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <tr>
                                    <th class="text-center fw-bold" style="width: 60px">#</th>
                                    <th class="fw-bold"><i class="ti ti-category me-1"></i>Kategori</th>
                                    <th class="fw-bold"><i class="ti ti-package me-1"></i>Nama Produk</th>
                                    <th class="fw-bold"><i class="ti ti-file-text me-1"></i>Deskripsi</th>
                                    <th class="fw-bold text-end"><i class="ti ti-currency-dollar me-1"></i>Harga</th>
                                    <th class="fw-bold text-center"><i class="ti ti-stack me-1"></i>Stok</th>
                                    <th class="fw-bold text-center"><i class="ti ti-photo me-1"></i>Gambar</th>
                                    <th class="fw-bold text-center" style="width:120px;"><i class="ti ti-settings me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fw-semibold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success-subtle rounded me-2">
                                                <i class="ti ti-package text-success"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $product->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ Str::limit($product->description ?? '-', 50) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-success">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($product->stock > 10)
                                            <span class="badge bg-success-subtle text-success">{{ $product->stock }}</span>
                                        @elseif($product->stock > 0)
                                            <span class="badge bg-warning-subtle text-warning">{{ $product->stock }}</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">{{ $product->stock }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/products/' . $product->image) }}" alt="Foto" 
                                                 class="rounded-3 border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="avatar-sm bg-light rounded d-inline-flex align-items-center justify-content-center">
                                                <i class="ti ti-photo text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm btnEditProduct" data-bs-toggle="tooltip" title="Edit"
                                                data-id="{{ $product->id }}" data-category="{{ $product->category_id }}"
                                                data-name="{{ $product->name }}" data-description="{{ $product->description }}"
                                                data-price="{{ $product->price }}" data-stock="{{ $product->stock }}"
                                                data-image="{{ $product->image }}">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm btnDeleteProduct" data-bs-toggle="tooltip" title="Hapus"
                                                data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-package text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="mt-2 text-muted">Belum ada produk</h6>
                                            <p class="text-muted mb-0">Klik tombol "Tambah Produk" untuk menambah data</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($products, 'hasPages') && $products->hasPages())
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-center">
                                {{ $products->links('pagination::bootstrap-4') }}
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