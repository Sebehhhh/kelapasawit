@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Promosi</h4>
        <button type="button" class="btn btn-primary" id="btnAddPromo">Tambah Promosi</button>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Produk</th>
                        <th>Periode</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promo)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $promo->title }}</td>
                            <td>{{ $promo->product->name ?? '-' }}</td>
                            <td>{{ $promo->start_date }} s/d {{ $promo->end_date }}</td>
                            <td>
                                @if($promo->image)
                                    <img src="{{ asset('storage/promotions/'.$promo->image) }}" alt="img" width="60">
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning btnEditPromo" data-id="{{ $promo->id }}"
                                    data-title="{{ $promo->title }}" data-description="{{ $promo->description }}"
                                    data-product_id="{{ $promo->product_id }}" data-start_date="{{ $promo->start_date }}"
                                    data-end_date="{{ $promo->end_date }}" data-image="{{ $promo->image }}">
                                    Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-danger btnDeletePromo" data-id="{{ $promo->id }}" data-title="{{ $promo->title }}">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada promosi.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $promotions->links() }}</div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Promosi -->
<div class="modal fade" id="promoModal" tabindex="-1" aria-labelledby="promoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="promoForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="promo_id" name="promo_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="promoModalLabel">Tambah Promosi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Produk</label>
                        <select name="product_id" id="product_id" class="form-control" required>
                            <option value="">- Pilih Produk -</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>
                        <div class="col">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Gambar</label>
                        <input type="file" name="image" id="image" class="form-control">
                        <div id="imgPreview" class="mt-2"></div>
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

<!-- SweetAlert2 & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // TOMBOL TAMBAH
    $('#btnAddPromo').click(function() {
        $('#promoForm')[0].reset();
        $('#promo_id').val('');
        $('#promoModalLabel').text('Tambah Promosi');
        $('#imgPreview').html('');
        $('#product_id').val('');
        $('#promoModal').modal('show');
    });

    // TOMBOL EDIT
    $('.btnEditPromo').click(function() {
        let btn = $(this);
        $('#promo_id').val(btn.data('id'));
        $('#title').val(btn.data('title'));
        $('#description').val(btn.data('description'));
        $('#start_date').val(btn.data('start_date'));
        $('#end_date').val(btn.data('end_date'));
        $('#product_id').val(btn.data('product_id'));
        $('#promoModalLabel').text('Edit Promosi');
        if(btn.data('image')) {
            $('#imgPreview').html(`<img src="/storage/promotions/${btn.data('image')}" width="80">`);
        } else {
            $('#imgPreview').html('');
        }
        $('#promoModal').modal('show');
    });

    // SUBMIT FORM (TAMBAH/EDIT)
    $('#promoForm').submit(function(e) {
        e.preventDefault();
        let id = $('#promo_id').val();
        let url = id ? '{{ route("admin.promotions.update", ":id") }}'.replace(':id', id) : '{{ route("admin.promotions.store") }}';
        let type = id ? 'POST' : 'POST';
        let formData = new FormData(this);
        if(id) formData.append('_method', 'PUT');
        $.ajax({
            url: url,
            type: type,
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $('#promoModal').modal('hide');
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
    $('.btnDeletePromo').click(function() {
        let id = $(this).data('id');
        let title = $(this).data('title');
        Swal.fire({
            title: `Hapus promosi?`,
            text: `Promosi "${title}" akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/promotions") }}/'+id,
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