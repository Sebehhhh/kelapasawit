@extends('layouts.app')
@section('title', 'Kelola Kategori Produk')
@section('content')
<div class="row mb-4">
  <div class="col-12 d-flex justify-content-between align-items-center">
    <h4 class="fw-bold mb-0">Daftar Kategori Produk</h4>
    <button type="button" class="btn btn-primary" id="btnAddCategory">
      <i class="ti ti-plus"></i> Tambah Kategori
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
            <th>Nama Kategori</th>
            <th>Deskripsi</th>
            {{-- <th>Dibuat</th> --}}
            <th class="text-center" style="width:120px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $cat)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $cat->name }}</td>
              <td>{{ $cat->description ?? '-' }}</td>
              {{-- <td>{{ $cat->created_at->format('d M Y') }}</td> --}}
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-info btnEditCategory"
                  data-id="{{ $cat->id }}"
                  data-name="{{ $cat->name }}"
                  data-description="{{ $cat->description }}">
                  <i class="ti ti-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger btnDeleteCategory"
                  data-id="{{ $cat->id }}"
                  data-name="{{ $cat->name }}">
                  <i class="ti ti-trash"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted">Belum ada data kategori.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">
      {{ $categories->links() }}
    </div>
  </div>
</div>

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