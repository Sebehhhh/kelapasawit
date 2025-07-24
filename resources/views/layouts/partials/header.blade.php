<!-- Header Start -->
<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
    </ul>

    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="drop2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('assets/images/profile/user-1.jpg') }}" alt="Foto Profil" width="35" height="35"
              class="rounded-circle">
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="drop2">
            <li>
              <a href="{{ auth()->user()->role === 'customer' ? route('customer.profile.edit') : route('admin.profile.edit') }}" class="dropdown-item d-flex align-items-center gap-2">
                <i class="ti ti-user fs-6"></i>
                <span>Profil Saya</span>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
              <button type="button" class="dropdown-item d-flex align-items-center gap-2" id="logout-btn">
                <i class="ti ti-logout fs-6"></i>
                <span>Logout</span>
              </button>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
<!-- Header End -->

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize Bootstrap dropdowns
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
    const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl));
    
    // Logout functionality
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', function () {
        Swal.fire({
          title: 'Yakin ingin logout?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, logout!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
          }
        });
      });
    }
  });
</script>