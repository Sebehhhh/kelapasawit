@extends('layouts.app')
@section('title', 'Kelola Kategori Produk')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="ti ti-category me-2"></i>
                                Kelola Kategori Produk
                            </h4>
                            <p class="mb-0 opacity-75">Manajemen kategori produk bibit sawit</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.printReport') }}" class="btn btn-light">
                                <i class="ti ti-file-download me-1"></i> Export PDF
                            </a>
                            <button type="button" class="btn btn-light" id="btnAddCategory">
                                <i class="ti ti-plus me-1"></i> Tambah Kategori
                            </button>
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
                        <i class="ti ti-list me-2 text-primary"></i>Daftar Kategori
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <tr>
                                    <th class="text-center fw-bold" style="width: 60px">#</th>
                                    <th class="fw-bold"><i class="ti ti-tag me-1"></i>Nama Kategori</th>
                                    <th class="fw-bold"><i class="ti ti-file-text me-1"></i>Deskripsi</th>
                                    <th class="fw-bold text-center" style="width:120px;"><i class="ti ti-settings me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $cat)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fw-semibold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-subtle rounded me-2">
                                                <i class="ti ti-tag text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $cat->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $cat->description ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm btnEditCategory" data-bs-toggle="tooltip" title="Edit"
                                                data-id="{{ $cat->id }}"
                                                data-name="{{ $cat->name }}"
                                                data-description="{{ $cat->description }}">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm btnDeleteCategory" data-bs-toggle="tooltip" title="Hapus"
                                                data-id="{{ $cat->id }}"
                                                data-name="{{ $cat->name }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-category text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="mt-2 text-muted">Belum ada kategori</h6>
                                            <p class="text-muted mb-0">Klik tombol "Tambah Kategori" untuk menambah data</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($categories, 'hasPages') && $categories->hasPages())
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-center">
                                {{ $categories->links('pagination::bootstrap-4') }}
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

<!-- Modal Tambah/Edit Kategori -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="categoryForm">
      @csrf
      <input type="hidden" id="category_id" name="category_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="categoryModalLabel">Tambah Kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" id="name" name="name" required maxlength="100">
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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

<!-- SweetAlert2 & jQuery CDN (gunakan kalau belum ada di layout) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
  // TOMBOL TAMBAH
  $('#btnAddCategory').click(function() {
    $('#categoryForm')[0].reset();
    $('#category_id').val('');
    $('#categoryModalLabel').text('Tambah Kategori');
    $('#categoryModal').modal('show');
  });

  // TOMBOL EDIT
  $('.btnEditCategory').click(function() {
    let btn = $(this);
    $('#category_id').val(btn.data('id'));
    $('#name').val(btn.data('name'));
    $('#description').val(btn.data('description'));
    $('#categoryModalLabel').text('Edit Kategori');
    $('#categoryModal').modal('show');
  });

  // SUBMIT FORM (TAMBAH/EDIT)
  $('#categoryForm').submit(function(e) {
    e.preventDefault();
    let id = $('#category_id').val();
    let url = id ? '{{ route("admin.categories.update", ":id") }}'.replace(':id', id) : '{{ route("admin.categories.store") }}';
    let type = id ? 'PUT' : 'POST';
    let formData = $(this).serialize();

    $.ajax({
      url: url,
      type: type === 'POST' ? 'POST' : 'POST', // Laravel update pakai POST + _method
      data: formData + (id ? '&_method=PUT' : ''),
      success: function(res) {
        $('#categoryModal').modal('hide');
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
  $('.btnDeleteCategory').click(function() {
    let id = $(this).data('id');
    let name = $(this).data('name');
    Swal.fire({
      title: `Hapus kategori?`,
      text: `Kategori "${name}" akan dihapus permanen.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '{{ url("admin/categories") }}/'+id,
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