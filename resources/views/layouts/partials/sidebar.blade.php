<aside class="left-sidebar">
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
        <img src="{{ asset('assets/images/logos/logo.svg') }}" alt="Logo" />
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-6"></i>
      </div>
    </div>
    <nav class="sidebar-nav scroll-sidebar" data-simplebar>
      <ul id="sidebarnav">
        <li class="nav-small-cap">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Menu</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('dashboard') }}">
            <i class="ti ti-home"></i>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>

        {{-- Hanya untuk admin --}}
        @if(auth()->user()->role === 'admin')
          <li class="nav-small-cap mt-2">
            <iconify-icon icon="solar:settings-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
            <span class="hide-menu">Admin Menu</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.products.index') }}">
              <i class="ti ti-package"></i>
              <span class="hide-menu">Manajemen Produk</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.categories.index') }}">
              <i class="ti ti-tags"></i>
              <span class="hide-menu">Kategori Produk</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.promotions.index') }}">
              <i class="ti ti-discount"></i>
              <span class="hide-menu">Promosi</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.orders.index') }}">
              <i class="ti ti-shopping-cart"></i>
              <span class="hide-menu">Order Masuk</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.users.index') }}">
              <i class="ti ti-users"></i>
              <span class="hide-menu">Akun Pengguna</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.reports.index') }}">
              <i class="ti ti-file-analytics"></i>
              <span class="hide-menu">Laporan Penjualan</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.testimonials.index') }}">
              <i class="ti ti-star"></i>
              <span class="hide-menu">Testimoni</span>
            </a>
          </li>
        @endif

        {{-- Hanya untuk customer --}}
        @if(auth()->user()->role === 'customer')
          <li class="nav-small-cap mt-2">
            <iconify-icon icon="solar:user-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
            <span class="hide-menu">Customer Menu</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('customer.products.index') }}">
              <i class="ti ti-package"></i>
              <span class="hide-menu">Katalog Bibit</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('customer.orders.index') }}">
              <i class="ti ti-shopping-cart"></i>
              <span class="hide-menu">Pesanan Saya</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('customer.payments.create') }}">
              <i class="ti ti-credit-card"></i>
              <span class="hide-menu">Pembayaran</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('customer.testimonials.index') }}">
              <i class="ti ti-star"></i>
              <span class="hide-menu">Testimoni</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('customer.profile.edit') }}">
              <i class="ti ti-user"></i>
              <span class="hide-menu">Profil Saya</span>
            </a>
          </li>
        @endif

      </ul>
    </nav>
  </div>
</aside>