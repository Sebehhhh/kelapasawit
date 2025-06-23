@extends('layouts.app')
@section('title', 'Kelola Akun Pengguna')
@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold mb-0">Daftar Akun Pengguna</h4>
        <button type="button" class="btn btn-primary" id="btnAddUser">
            <i class="ti ti-user-plus"></i> Tambah Pengguna
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
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th class="text-center" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            <span
                                class="badge bg-{{ $user->role == 'admin' ? 'primary' : ($user->role == 'owner' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-info btnEditUser" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                data-phone="{{ $user->phone }}" data-role="{{ $user->role }}">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btnDeleteUser" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $users->links() }}
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
                            <option value="pelanggan">Pelanggan</option>
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