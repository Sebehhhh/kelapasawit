@extends('layouts.app')
@section('title', 'Kelola Akun Pengguna')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div class="card-body text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="ti ti-users me-2"></i>
                                Kelola Akun Pengguna
                            </h4>
                            <p class="mb-0 opacity-75">Manajemen akun pengguna sistem</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.printReport') }}" class="btn btn-dark">
                                <i class="ti ti-file-download me-1"></i> Export PDF
                            </a>
                            <button type="button" class="btn btn-dark" id="btnAddUser">
                                <i class="ti ti-user-plus me-1"></i> Tambah Pengguna
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
                            <label for="filter_name" class="form-label mb-2 fw-semibold">
                                <i class="ti ti-user me-1 text-primary"></i> Nama
                            </label>
                            <input type="text" name="name" id="filter_name" class="form-control" placeholder="Cari nama..." value="{{ $searchName ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label for="filter_email" class="form-label mb-2 fw-semibold">
                                <i class="ti ti-mail me-1 text-primary"></i> Email
                            </label>
                            <input type="text" name="email" id="filter_email" class="form-control" placeholder="Cari email..." value="{{ $searchEmail ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label for="filter_role" class="form-label mb-2 fw-semibold">
                                <i class="ti ti-user-check me-1 text-primary"></i> Role
                            </label>
                            <select name="role" id="filter_role" class="form-select">
                                <option value="">Semua Role</option>
                                <option value="admin" @if(isset($selectedRole) && $selectedRole=='admin') selected @endif>Admin</option>
                                <option value="owner" @if(isset($selectedRole) && $selectedRole=='owner') selected @endif>Owner</option>
                                <option value="customer" @if(isset($selectedRole) && $selectedRole=='customer') selected @endif>Customer</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-refresh me-1"></i> Reset
                            </a>
                        </div>
                    </form>
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
                        <i class="ti ti-list me-2 text-primary"></i>Daftar Pengguna
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <tr>
                                    <th class="text-center fw-bold" style="width: 60px">#</th>
                                    <th class="fw-bold"><i class="ti ti-user me-1"></i>Nama</th>
                                    <th class="fw-bold"><i class="ti ti-mail me-1"></i>Email</th>
                                    <th class="fw-bold"><i class="ti ti-phone me-1"></i>No HP</th>
                                    <th class="fw-bold text-center"><i class="ti ti-user-check me-1"></i>Role</th>
                                    <th class="fw-bold"><i class="ti ti-calendar me-1"></i>Terdaftar</th>
                                    <th class="fw-bold text-center" style="width:120px;"><i class="ti ti-settings me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fw-semibold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-{{ $user->role == 'admin' ? 'primary' : ($user->role == 'owner' ? 'warning' : 'secondary') }}-subtle rounded me-2">
                                                <i class="ti ti-user text-{{ $user->role == 'admin' ? 'primary' : ($user->role == 'owner' ? 'warning' : 'secondary') }}"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $user->email }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $user->phone ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $user->role == 'admin' ? 'primary' : ($user->role == 'owner' ? 'warning' : 'secondary') }}-subtle text-{{ $user->role == 'admin' ? 'primary' : ($user->role == 'owner' ? 'warning' : 'secondary') }} px-3 py-2 rounded-pill">
                                            @if($user->role == 'admin')
                                                <i class="ti ti-shield-check me-1"></i>
                                            @elseif($user->role == 'owner')
                                                <i class="ti ti-crown me-1"></i>
                                            @else
                                                <i class="ti ti-user me-1"></i>
                                            @endif
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="ti ti-calendar me-1"></i>
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm btnEditUser" data-bs-toggle="tooltip" title="Edit" 
                                                data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                                data-phone="{{ $user->phone }}" data-role="{{ $user->role }}">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm btnDeleteUser" data-bs-toggle="tooltip" title="Hapus" 
                                                data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-users text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="mt-2 text-muted">Belum ada pengguna</h6>
                                            <p class="text-muted mb-0">Klik tombol "Tambah Pengguna" untuk menambah data</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($users, 'hasPages') && $users->hasPages())
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-center">
                                {{ $users->links('pagination::bootstrap-4') }}
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
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-center">
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Pengguna -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="userForm">
            @csrf
            <input type="hidden" id="user_id" name="user_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="userModalLabel">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="owner">Owner</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>
                    <div class="mb-3 password-field">
                        <label for="password" class="form-label">Password <span id="passwordNote"
                                class="text-muted small"></span></label>
                        <input type="password" class="form-control" id="password" name="password">
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

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery CDN (atau pastikan sudah ada di layout) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
  // TOMBOL TAMBAH
  $('#btnAddUser').click(function() {
    $('#userForm')[0].reset();
    $('#user_id').val('');
    $('#userModalLabel').text('Tambah Pengguna');
    $('#password').attr('required', true);
    $('#passwordNote').text('(wajib diisi)');
    $('#userModal').modal('show');
  });

  // TOMBOL EDIT
  $('.btnEditUser').click(function() {
    let btn = $(this);
    $('#user_id').val(btn.data('id'));
    $('#name').val(btn.data('name'));
    $('#email').val(btn.data('email'));
    $('#phone').val(btn.data('phone'));
    $('#role').val(btn.data('role'));
    $('#userModalLabel').text('Edit Pengguna');
    $('#password').attr('required', false);
    $('#passwordNote').text('(kosongkan jika tidak diubah)');
    $('#userModal').modal('show');
  });

  // SUBMIT FORM (TAMBAH/EDIT)
  $('#userForm').submit(function(e) {
    e.preventDefault();
    let id = $('#user_id').val();
    let url = id ? '{{ route("admin.users.update", ":id") }}'.replace(':id', id) : '{{ route("admin.users.store") }}';
    let type = id ? 'PUT' : 'POST';
    let formData = $(this).serialize();

    $.ajax({
      url: url,
      type: type === 'POST' ? 'POST' : 'POST', // Laravel resource update pakai POST + _method
      data: formData + (id ? '&_method=PUT' : ''),
      success: function(res) {
        $('#userModal').modal('hide');
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
  $('.btnDeleteUser').click(function() {
    let id = $(this).data('id');
    let name = $(this).data('name');
    Swal.fire({
      title: `Hapus pengguna?`,
      text: `Akun "${name}" akan dihapus permanen.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '{{ url("admin/users") }}/'+id,
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