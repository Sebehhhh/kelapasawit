<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Sistem Informasi Penjualan Bibit Kelapa Sawit UKM</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  {{-- SweetAlert2 CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    .bg-login {
      background: linear-gradient(135deg, #1e5f2f, #3c8d40);
    }
    .login-card {
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    .login-header {
      background-color: #f8f9fa;
      padding: 20px;
      border-bottom: 1px solid #eee;
    }
    .login-icon {
      color: #2e7d32;
      font-size: 24px;
      margin-right: 10px;
    }
    .btn-login {
      background: linear-gradient(135deg, #2e7d32, #388e3c);
      border: none;
      height: 48px;
      transition: all 0.3s;
    }
    .btn-login:hover {
      background: linear-gradient(135deg, #388e3c, #2e7d32);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
    }
  </style>
</head>

<body class="bg-login">
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-4">
            <div class="card mb-0 login-card">
              <div class="login-header text-center">
                <h3 class="fw-bold mb-0 py-3">
                  <i class="fas fa-seedling login-icon"></i>
                  Sistem Kelapa Sawit
                </h3>
              </div>
              <div class="card-body p-4 p-md-5">
                <h4 class="fw-bold mb-4 text-center">Selamat Datang</h4>
                <p class="text-center text-muted mb-4">Sistem Informasi Promosi & Penjualan Bibit Kelapa Sawit UKM</p>

                {{-- Form Login --}}
                <form method="POST" action="{{ route('login') }}">
                  @csrf

                  {{-- Input Email --}}
                  <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-envelope text-muted"></i>
                      </span>
                      <input type="email"
                        class="form-control border-start-0 @error('email') is-invalid @enderror"
                        id="email" name="email" value="{{ old('email') }}" placeholder="nama@example.com" required autocomplete="email" autofocus>
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
                      <input type="password"
                        class="form-control border-start-0 @error('password') is-invalid @enderror"
                        id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                    </div>
                    @error('password')
                      <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  {{-- Remember Me --}}
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                      <label class="form-check-label" for="remember">
                        Ingat Saya
                      </label>
                    </div>
                    @if (Route::has('password.request'))
                      <a class="text-success fw-semibold" href="{{ route('password.request') }}">Lupa Password?</a>
                    @endif
                  </div>

                  {{-- Tombol Login --}}
                  <button type="submit" class="btn btn-login w-100 fs-5 mb-4 rounded-3 text-white fw-semibold">Masuk</button>

                  {{-- Teks Tambahan --}}
                  <div class="text-center mt-4">
                    <p class="mb-0"><span class="fw-semibold">Belum punya akun?</span> Silakan hubungi admin usaha</p>
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
        text: "{{ session('status') }}",
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