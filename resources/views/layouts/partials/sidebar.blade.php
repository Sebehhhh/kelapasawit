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
          {{-- Kosong, nanti diisi menu khusus admin --}}
          {{-- Contoh: --}}
          {{-- <li class="sidebar-item">
            <a class="sidebar-link" href="#">
              <i class="ti ti-settings"></i>
              <span class="hide-menu">Menu Admin</span>
            </a>
          </li> --}}
        @endif

        {{-- Hanya untuk customer --}}
        @if(auth()->user()->role === 'customer')
          {{-- Kosong, nanti diisi menu khusus customer --}}
          {{-- Contoh: --}}
          {{-- <li class="sidebar-item">
            <a class="sidebar-link" href="#">
              <i class="ti ti-shopping-cart"></i>
              <span class="hide-menu">Menu Customer</span>
            </a>
          </li> --}}
        @endif

      </ul>
    </nav>
  </div>
</aside>