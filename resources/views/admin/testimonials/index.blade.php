@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Testimoni</h4>
        <button type="button" class="btn btn-primary" id="btnAddTesti">Tambah Testimoni</button>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Produk</th>
                        <th>Pesan</th>
                        <th>Rating</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $testi->user->name ?? '-' }}</td>
                            <td>{{ $testi->product->name ?? '-' }}</td>
                            <td>{{ $testi->message }}</td>
                            <td>{{ $testi->rating }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning btnEditTesti" data-id="{{ $testi->id }}"
                                    data-user_id="{{ $testi->user_id }}" data-product_id="{{ $testi->product_id }}"
                                    data-message="{{ $testi->message }}" data-rating="{{ $testi->rating }}">
                                    Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-danger btnDeleteTesti" data-id="{{ $testi->id }}">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada testimoni.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $testimonials->links() }}</div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Testimoni -->
<div class="modal fade" id="testiModal" tabindex="-1" aria-labelledby="testiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="testiForm">
            @csrf
            <input type="hidden" id="testi_id" name="testi_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="testiModalLabel">Tambah Testimoni</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>User</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value="">- Pilih User -</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
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
                    <div class="mb-3">
                        <label>Pesan</label>
                        <textarea name="message" id="message" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Rating</label>
                        <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" required>
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
    $('#btnAddTesti').click(function() {
        $('#testiForm')[0].reset();
        $('#testi_id').val('');
        $('#testiModalLabel').text('Tambah Testimoni');
        $('#user_id').val('');
        $('#product_id').val('');
        $('#testiModal').modal('show');
    });

    // TOMBOL EDIT
    $('.btnEditTesti').click(function() {
        let btn = $(this);
        $('#testi_id').val(btn.data('id'));
        $('#user_id').val(btn.data('user_id'));
        $('#product_id').val(btn.data('product_id'));
        $('#message').val(btn.data('message'));
        $('#rating').val(btn.data('rating'));
        $('#testiModalLabel').text('Edit Testimoni');
        $('#testiModal').modal('show');
    });

    // SUBMIT FORM (TAMBAH/EDIT)
    $('#testiForm').submit(function(e) {
        e.preventDefault();
        let id = $('#testi_id').val();
        let url = id ? '{{ route("admin.testimonials.update", ":id") }}'.replace(':id', id) : '{{ route("admin.testimonials.store") }}';
        let type = id ? 'POST' : 'POST';
        let formData = $(this).serialize();
        if(id) formData += '&_method=PUT';
        $.ajax({
            url: url,
            type: type,
            data: formData,
            success: function(res) {
                $('#testiModal').modal('hide');
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
    $('.btnDeleteTesti').click(function() {
        let id = $(this).data('id');
        Swal.fire({
            title: `Hapus testimoni?`,
            text: `Testimoni ini akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/testimonials") }}/'+id,
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