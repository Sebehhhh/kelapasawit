<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Akun Baru - Sistem Informasi Penjualan Bibit Kelapa Sawit UKM</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  {{-- SweetAlert2 CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    .bg-register {
      background: linear-gradient(135deg, #1e5f2f, #3c8d40);
    }
    .register-card {
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    .register-header {
      background-color: #f8f9fa;
      padding: 20px;
      border-bottom: 1px solid #eee;
    }
    .register-icon {
      color: #2e7d32;
      font-size: 24px;
      margin-right: 10px;
    }
    .btn-register {
      background: linear-gradient(135deg, #2e7d32, #388e3c);
      border: none;
      height: 48px;
      transition: all 0.3s;
    }
    .btn-register:hover {
      background: linear-gradient(135deg, #388e3c, #2e7d32);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
    }
  </style>
</head>

<body class="bg-register">
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-4">
            <div class="card mb-0 register-card">
              <div class="register-header text-center">
                <h3 class="fw-bold mb-0 py-3">
                  <i class="fas fa-user-plus register-icon"></i>
                  Daftar Akun Baru
                </h3>
              </div>
              <div class="card-body p-4 p-md-5">
                <p class="text-center text-muted mb-4">Sistem Informasi Promosi & Penjualan Bibit Kelapa Sawit UKM</p>
                {{-- Form Register --}}
                <form method="POST" action="{{ route('register') }}">
                  @csrf

                  {{-- Input Nama Lengkap --}}
                  <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-user text-muted"></i>
                      </span>
                      <input id="name" type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama lengkap">
                    </div>
                    @error('name')
                      <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  {{-- Input Email --}}
                  <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-envelope text-muted"></i>
                      </span>
                      <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@example.com">
                    </div>
                    @error('email')
                      <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  {{-- Input Password --}}
                  <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-lock text-muted"></i>
                      </span>
                      <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                    </div>
                    @error('password')
                      <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  {{-- Input Konfirmasi Password --}}
                  <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-lock text-muted"></i>
                      </span>
                      <input id="password_confirmation" type="password" class="form-control border-start-0 @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
                    </div>
                    @error('password_confirmation')
                      <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  {{-- Tombol Daftar --}}
                  <button type="submit" class="btn btn-register w-100 fs-5 mb-4 rounded-3 text-white fw-semibold">Daftar</button>

                  {{-- Teks Tambahan --}}
                  <div class="text-center mt-4">
                    <p class="mb-0"><span class="fw-semibold">Sudah punya akun?</span> <a href="{{ route('login') }}" class="text-success fw-semibold">Login di sini</a></p>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

  {{-- SweetAlert Script --}}
  <script>
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: "{{ session('success') }}",
        confirmButtonColor: '#2e7d32',
        confirmButtonText: 'OK'
      });
    @endif
    
    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: "{{ session('error') }}",
        confirmButtonColor: '#2e7d32',
        confirmButtonText: 'OK'
      });
    @endif
  </script>
</body>

</html>
