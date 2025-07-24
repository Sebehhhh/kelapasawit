<aside class="left-sidebar">
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="{{ route('dashboard') }}" class="text-nowrap fs-4 fw-bold text-dark d-flex align-items-center">
        <img src="{{ asset('assets/images/logos/logo.png') }}" alt="Logo" style="height:32px;width:auto;margin-right:8px;">
        Kelapa Sawit
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
              <span class="hide-menu">Difiturkan</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.orders.index') }}">
              <i class="ti ti-shopping-cart"></i>
              <span class="hide-menu">Order Masuk</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.purchase-invoices.index') }}">
              <i class="ti ti-truck-delivery"></i>
              <span class="hide-menu">Barang Masuk</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.users.index') }}">
              <i class="ti ti-users"></i>
              <span class="hide-menu">Akun Pengguna</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('admin.testimonials.index') }}">
              <i class="ti ti-star"></i>
              <span class="hide-menu">Testimoni</span>
            </a>
          </li>
          
          <!-- Menu Report -->
          <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <i class="ti ti-file-report"></i>
              <span class="hide-menu">Laporan</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              <li class="sidebar-item">
                <a href="{{ route('admin.products.sawitUnggulReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Sawit Unggul</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('admin.products.sawitLokalReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Sawit Lokal</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('admin.products.sawitImporReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Sawit Impor</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('admin.promotions.printReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Produk Difiturkan</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('admin.purchase-invoices.printReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Pembelian Pemasok</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('admin.orders.strukMasuk') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Struk Masuk</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('admin.orders.strukKeluar') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Struk Keluar</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('admin.orders.salesReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Hasil Penjualan</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('admin.products.stokReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Stok Barang</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('admin.users.printReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Pengguna</span>
                </a>
              </li>
            </ul>
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
            <a class="sidebar-link" href="{{ route('customer.payments.index') }}">
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
        @endif

        {{-- Hanya untuk owner --}}
        @if(auth()->user()->role === 'owner')
          <li class="nav-small-cap mt-2">
            <iconify-icon icon="solar:monitor-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
            <span class="hide-menu">Owner Menu</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('owner.products.index') }}">
              <i class="ti ti-package"></i>
              <span class="hide-menu">Produk</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('owner.orders.index') }}">
              <i class="ti ti-shopping-cart"></i>
              <span class="hide-menu">Order</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('owner.promotions.index') }}">
              <i class="ti ti-discount"></i>
              <span class="hide-menu">Difiturkan</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('owner.testimonials.index') }}">
              <i class="ti ti-star"></i>
              <span class="hide-menu">Testimoni</span>
            </a>
          </li>
          
          <!-- Menu Report Owner - Same as Admin -->
          <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <i class="ti ti-file-report"></i>
              <span class="hide-menu">Laporan</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              <li class="sidebar-item">
                <a href="{{ route('owner.products.sawitUnggulReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Sawit Unggul</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('owner.products.sawitLokalReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Sawit Lokal</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('owner.products.sawitImporReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Sawit Impor</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('owner.promotions.printReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Produk Difiturkan</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('owner.purchase-invoices.printReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Pembelian Pemasok</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('owner.orders.strukMasuk') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Struk Masuk</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('owner.orders.strukKeluar') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Struk Keluar</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('owner.orders.salesReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Hasil Penjualan</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('owner.products.stokReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Stok Barang</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="{{ route('owner.users.printReport') }}" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Pengguna</span>
                </a>
              </li>
            </ul>
          </li>
        @endif

      </ul>
    </nav>
  </div>
</aside>