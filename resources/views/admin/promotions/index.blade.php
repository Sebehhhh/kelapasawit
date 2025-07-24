@extends('layouts.app')
@section('title', 'Kelola Promosi')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="ti ti-speakerphone me-2"></i>
                                Kelola Promosi & Diskon
                            </h4>
                            <p class="mb-0 opacity-75">Manajemen promosi dan diskon untuk produk</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.promotions.printReport') }}" class="btn btn-light">
                                <i class="ti ti-file-download me-1"></i> Export PDF
                            </a>
                            <button type="button" class="btn btn-light" id="btnAddPromo">
                                <i class="ti ti-plus me-1"></i> Tambah Promosi
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
                        <i class="ti ti-list me-2 text-primary"></i>Daftar Promosi & Diskon
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <tr>
                                    <th class="text-center fw-bold" style="width: 60px">#</th>
                                    <th class="fw-bold"><i class="ti ti-text-caption me-1"></i>Judul</th>
                                    <th class="fw-bold"><i class="ti ti-package me-1"></i>Produk</th>
                                    <th class="fw-bold text-center"><i class="ti ti-discount-2 me-1"></i>Diskon</th>
                                    <th class="fw-bold"><i class="ti ti-calendar me-1"></i>Periode</th>
                                    <th class="fw-bold text-center"><i class="ti ti-photo me-1"></i>Gambar</th>
                                    <th class="fw-bold text-center"><i class="ti ti-circle-check me-1"></i>Status</th>
                                    <th class="fw-bold text-center" style="width:120px;"><i class="ti ti-settings me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promotions as $promo)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fw-semibold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-warning-subtle rounded me-2">
                                                <i class="ti ti-speakerphone text-warning"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $promo->title }}</h6>
                                                <small class="text-muted">{{ Str::limit($promo->description ?? '', 30) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $promo->product->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($promo->discount_value > 0)
                                            <span class="badge bg-danger text-white fw-bold">
                                                @if($promo->discount_type === 'percentage')
                                                    {{ $promo->discount_value }}%
                                                @else
                                                    Rp {{ number_format($promo->discount_value, 0, ',', '.') }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Tidak ada diskon</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="badge bg-success-subtle text-success mb-1">
                                                <i class="ti ti-calendar-event me-1"></i>
                                                {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
                                            </span>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ti ti-calendar-x me-1"></i>
                                                {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($promo->image)
                                            <img src="{{ asset('storage/promotions/'.$promo->image) }}" alt="Promo" 
                                                 class="rounded-3 border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="avatar-sm bg-light rounded d-inline-flex align-items-center justify-content-center">
                                                <i class="ti ti-photo text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($promo->is_active && $promo->start_date <= now() && $promo->end_date >= now())
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($promo->end_date < now())
                                            <span class="badge bg-danger">Kadaluarsa</span>
                                        @elseif($promo->start_date > now())
                                            <span class="badge bg-warning">Akan Datang</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm btnEditPromo" data-bs-toggle="tooltip" title="Edit"
                                                data-id="{{ $promo->id }}" data-title="{{ $promo->title }}" data-description="{{ $promo->description }}"
                                                data-product_id="{{ $promo->product_id }}" data-start_date="{{ $promo->start_date }}"
                                                data-end_date="{{ $promo->end_date }}" data-image="{{ $promo->image }}"
                                                data-discount_type="{{ $promo->discount_type }}" data-discount_value="{{ $promo->discount_value }}"
                                                data-min_purchase="{{ $promo->min_purchase }}" data-max_discount="{{ $promo->max_discount }}"
                                                data-is_active="{{ $promo->is_active ? 1 : 0 }}">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm btnDeletePromo" data-bs-toggle="tooltip" title="Hapus"
                                                data-id="{{ $promo->id }}" data-title="{{ $promo->title }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-speakerphone text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="mt-2 text-muted">Belum ada promosi</h6>
                                            <p class="text-muted mb-0">Klik tombol "Tambah Promosi" untuk menambah data</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($promotions, 'hasPages') && $promotions->hasPages())
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-center">
                                {{ $promotions->links('pagination::bootstrap-4') }}
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
                    <div class="mb-3 row">
                        <div class="col">
                            <label>Tipe Diskon</label>
                            <select name="discount_type" id="discount_type" class="form-control" required>
                                <option value="">- Pilih Tipe Diskon -</option>
                                <option value="percentage">Persentase (%)</option>
                                <option value="fixed">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>Nilai Diskon</label>
                            <input type="number" name="discount_value" id="discount_value" class="form-control" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label>Min. Pembelian (Opsional)</label>
                            <input type="number" name="min_purchase" id="min_purchase" class="form-control" min="0" step="0.01">
                        </div>
                        <div class="col">
                            <label>Maks. Diskon (Opsional)</label>
                            <input type="number" name="max_discount" id="max_discount" class="form-control" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Promosi Aktif
                            </label>
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
        $('#discount_type').val(btn.data('discount_type'));
        $('#discount_value').val(btn.data('discount_value'));
        $('#min_purchase').val(btn.data('min_purchase'));
        $('#max_discount').val(btn.data('max_discount'));
        $('#is_active').prop('checked', btn.data('is_active') == 1);
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