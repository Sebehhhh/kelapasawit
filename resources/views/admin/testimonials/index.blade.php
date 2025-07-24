@extends('layouts.app')
@section('title', 'Kelola Testimoni')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                <div class="card-body text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="ti ti-message-star me-2"></i>
                                Kelola Testimoni
                            </h4>
                            <p class="mb-0 opacity-75">Manajemen testimoni dan ulasan pelanggan</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-dark" onclick="alert('Fitur export PDF akan segera tersedia')">
                                <i class="ti ti-file-download me-1"></i> Export PDF
                            </a>
                            <button type="button" class="btn btn-dark" id="btnAddTesti">
                                <i class="ti ti-plus me-1"></i> Tambah Testimoni
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
                        <i class="ti ti-list me-2 text-primary"></i>Daftar Testimoni
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <tr>
                                    <th class="text-center fw-bold" style="width: 60px">#</th>
                                    <th class="fw-bold"><i class="ti ti-user me-1"></i>User</th>
                                    <th class="fw-bold"><i class="ti ti-package me-1"></i>Produk</th>
                                    <th class="fw-bold"><i class="ti ti-message me-1"></i>Pesan</th>
                                    <th class="fw-bold text-center"><i class="ti ti-star me-1"></i>Rating</th>
                                    <th class="fw-bold text-center" style="width:120px;"><i class="ti ti-settings me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($testimonials as $testi)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fw-semibold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info-subtle rounded me-2">
                                                <i class="ti ti-user text-info"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $testi->user->name ?? '-' }}</h6>
                                                <small class="text-muted">{{ $testi->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $testi->product->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-start">
                                            <div class="avatar-sm bg-warning-subtle rounded me-2 flex-shrink-0">
                                                <i class="ti ti-message text-warning"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 text-muted">{{ Str::limit($testi->message, 80) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $testi->rating)
                                                    <i class="ti ti-star-filled text-warning"></i>
                                                @else
                                                    <i class="ti ti-star text-muted"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-2 badge bg-warning-subtle text-warning">{{ $testi->rating }}/5</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm btnEditTesti" data-bs-toggle="tooltip" title="Edit"
                                                data-id="{{ $testi->id }}" data-user_id="{{ $testi->user_id }}" data-product_id="{{ $testi->product_id }}"
                                                data-message="{{ $testi->message }}" data-rating="{{ $testi->rating }}">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm btnDeleteTesti" data-bs-toggle="tooltip" title="Hapus"
                                                data-id="{{ $testi->id }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-message-star text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="mt-2 text-muted">Belum ada testimoni</h6>
                                            <p class="text-muted mb-0">Klik tombol "Tambah Testimoni" untuk menambah data</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($testimonials, 'hasPages') && $testimonials->hasPages())
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-center">
                                {{ $testimonials->links('pagination::bootstrap-4') }}
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