@extends('layouts.app')
@section('title', 'Katalog Bibit Sawit')
@section('content')

{{-- Header Section with Search and Filter --}}
<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 180px;">
            <div class="card-body d-flex align-items-center text-white">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2">
                                <i class="ti ti-leaf me-2"></i>Katalog Bibit Kelapa Sawit
                            </h2>
                            <p class="mb-3 opacity-90">Temukan bibit berkualitas tinggi untuk investasi masa depan Anda</p>
                            <div class="d-flex gap-2 text-sm">
                                <span class="badge bg-white text-dark px-3 py-2">
                                    <i class="ti ti-shield-check me-1"></i>Kualitas Terjamin
                                </span>
                                <span class="badge bg-white text-dark px-3 py-2">
                                    <i class="ti ti-truck me-1"></i>Pengiriman Aman
                                </span>
                                <span class="badge bg-white text-dark px-3 py-2">
                                    <i class="ti ti-discount-2 me-1"></i>Harga Terbaik
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-end d-none d-md-block">
                            <i class="ti ti-plant-2 opacity-30" style="font-size: 8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Search and Filter Bar --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="ti ti-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-0 bg-light" 
                                   placeholder="Cari produk bibit..." id="searchProducts">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select border-0 bg-light" id="filterCategory">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ Str::slug($category->name) }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select border-0 bg-light" id="sortProducts">
                            <option value="newest">Terbaru</option>
                            <option value="price-low">Harga Terendah</option>
                            <option value="price-high">Harga Tertinggi</option>
                            <option value="name">Nama A-Z</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Products Stats --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <span class="text-primary fw-bold" id="productCount">{{ $products->total() }}</span> 
                    Produk Tersedia
                </h5>
                <small class="text-muted">Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</small>
            </div>
            <div class="d-flex gap-2 view-toggle">
                <button class="btn btn-outline-secondary btn-sm active" id="gridView" title="Grid View">
                    <i class="ti ti-grid-dots"></i>
                </button>
                <button class="btn btn-outline-secondary btn-sm" id="listView" title="List View">
                    <i class="ti ti-list"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Products Grid --}}
<div id="productsContainer">
    {{-- Grid View --}}
    <div class="row" id="gridContainer">
        @forelse($products as $product)
    <div class="col-lg-4 col-md-6 mb-4 product-item" data-category="{{ Str::slug($product->category->name ?? '') }}" data-name="{{ strtolower($product->name) }}" data-price="{{ $product->price }}">
        <div class="card border-0 shadow-sm h-100 position-relative product-card overflow-hidden">
            @php
                $hasDiscount = $product->hasActiveDiscount();
                $activePromotion = $product->getActivePromotion();
            @endphp
            
            {{-- Discount Badge --}}
            @if($hasDiscount)
                <div class="position-absolute top-0 start-0 m-3 z-index-10">
                    <span class="badge bg-gradient text-white px-3 py-2 rounded-pill shadow-sm discount-badge">
                        <i class="ti ti-discount-2 me-1"></i>
                        @if($activePromotion->discount_type === 'percentage')
                            {{ $activePromotion->discount_value }}% OFF
                        @else
                            Rp {{ number_format($activePromotion->discount_value, 0, ',', '.') }}
                        @endif
                    </span>
                </div>
            @endif
            
            {{-- Stock Badge --}}
            <div class="position-absolute top-0 end-0 m-3 z-index-10">
                <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }} px-2 py-1 rounded-pill">
                    {{ $product->stock > 0 ? $product->stock . ' tersedia' : 'Habis' }}
                </span>
            </div>
            
            {{-- Product Image --}}
            <div class="card-img-top position-relative overflow-hidden product-image-container" style="height: 220px;">
                <div class="image-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                    <div class="quick-view-btn">
                        <button class="btn btn-light btn-sm rounded-circle shadow btn-show-detail"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}" 
                                data-category="{{ $product->category->name ?? '-' }}"
                                data-description="{{ $product->description ?? '-' }}"
                                data-price="Rp {{ number_format($hasDiscount ? $product->getFinalPrice(1) : $product->price, 0, ',', '.') }}"
                                data-price-numeric="{{ $hasDiscount ? $product->getFinalPrice(1) : $product->price }}"
                                data-original-price="{{ $product->price }}"
                                data-has-discount="{{ $hasDiscount ? 'true' : 'false' }}"
                                data-discount-text="{{ $hasDiscount ? ($activePromotion->discount_type === 'percentage' ? $activePromotion->discount_value.'% OFF' : 'Diskon Rp '.number_format($activePromotion->discount_value, 0, ',', '.')) : '' }}"
                                data-image="{{ $product->image ? asset('storage/products/'.$product->image) : asset('assets/images/no-image.png') }}">
                            <i class="ti ti-eye"></i>
                        </button>
                    </div>
                </div>
                @if($product->image)
                <img src="{{ asset('storage/products/'.$product->image) }}" alt="{{ $product->name }}"
                     class="w-100 h-100 product-image" style="object-fit: cover; transition: transform 0.3s ease;">
                @else
                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                    <i class="ti ti-photo text-muted" style="font-size: 3rem;"></i>
                </div>
                @endif
            </div>
            
            {{-- Product Info --}}
            <div class="card-body d-flex flex-column p-4">
                {{-- Grid View Layout --}}
                <div class="grid-view-content">
                    {{-- Category --}}
                    <div class="mb-2">
                        <span class="badge bg-light text-dark rounded-pill px-3 py-1 product-category">
                            <i class="ti ti-tag me-1"></i>{{ $product->category->name ?? 'Umum' }}
                        </span>
                    </div>
                    
                    {{-- Product Name --}}
                    <h5 class="fw-bold mb-2 product-name">{{ $product->name }}</h5>
                    
                    {{-- Rating/Quality Indicator --}}
                    <div class="mb-3 rating-section">
                        <div class="d-flex align-items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="ti ti-star-filled text-warning small"></i>
                            @endfor
                            <small class="text-muted ms-1">(5.0) Kualitas Premium</small>
                        </div>
                    </div>
                </div>

                {{-- List View Layout --}}
                <div class="product-info d-none">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    @if($hasDiscount)
                                        <small class="text-success">
                                            <i class="ti ti-discount-2 me-1"></i>
                                            @if($activePromotion->discount_type === 'percentage')
                                                {{ $activePromotion->discount_value }}% OFF
                                            @else
                                                Diskon {{ number_format($activePromotion->discount_value, 0, ',', '.') }}
                                            @endif
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <span class="badge bg-light text-dark px-2 py-1 small">
                                {{ $product->category->name ?? 'Umum' }}
                            </span>
                        </div>
                        <div class="col-1">
                            <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning text-dark' : 'bg-danger') }} px-2 py-1 small">
                                {{ $product->stock }}
                            </span>
                        </div>
                        <div class="col-1">
                            @if($hasDiscount)
                                @php
                                    $originalPrice = (float) $product->price;
                                    $finalPrice = $product->getFinalPrice(1);
                                    $discountPercent = round((($originalPrice - $finalPrice) / $originalPrice) * 100);
                                @endphp
                                <span class="badge bg-danger text-white px-2 py-1 small">
                                    {{ $discountPercent }}%
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </div>
                        <div class="col-2 text-end">
                            @if($hasDiscount)
                                @php
                                    $originalPrice = (float) $product->price;
                                    $finalPrice = $product->getFinalPrice(1);
                                @endphp
                                <div class="text-muted text-decoration-line-through small">
                                    Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                </div>
                                <div class="fw-bold text-danger">
                                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                </div>
                            @else
                                <div class="fw-bold text-primary">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-2 text-end">
                            @if($product->stock > 0)
                                <button type="button" class="btn btn-primary btn-sm btn-checkout" 
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}" 
                                        data-stock="{{ $product->stock }}"
                                        data-price-numeric="{{ $hasDiscount ? $product->getFinalPrice(1) : $product->price }}"
                                        data-original-price="{{ $product->price }}"
                                        data-has-discount="{{ $hasDiscount ? 'true' : 'false' }}"
                                        data-image="{{ $product->image ? asset('storage/products/'.$product->image) : asset('assets/images/no-image.png') }}">
                                    <i class="ti ti-shopping-cart me-1"></i>Pesan
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="ti ti-ban me-1"></i>Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Price Section --}}
                @if($hasDiscount)
                    @php
                        $originalPrice = (float) $product->price;
                        $finalPrice = $product->getFinalPrice(1);
                        $discountAmount = $product->getDiscountAmount(1);
                        $activePromotion = $product->getActivePromotion();
                        
                        // Manual calculation untuk memastikan nilai benar - bypass min_purchase untuk single item
                        if ($activePromotion && $activePromotion->isActive() && $activePromotion->discount_value > 0) {
                            $shouldApplyDiscount = true;
                            
                            // Cek min_purchase hanya jika ada nilai yang ditetapkan
                            if ($activePromotion->min_purchase && $originalPrice < $activePromotion->min_purchase) {
                                $shouldApplyDiscount = false;
                            }
                            
                            if ($shouldApplyDiscount) {
                                if ($activePromotion->discount_type === 'percentage') {
                                    $manualDiscountAmount = round(($originalPrice * $activePromotion->discount_value) / 100, 2);
                                    $manualFinalPrice = round($originalPrice - $manualDiscountAmount, 2);
                                    
                                    // Apply max_discount if set
                                    if ($activePromotion->max_discount && $manualDiscountAmount > $activePromotion->max_discount) {
                                        $manualDiscountAmount = $activePromotion->max_discount;
                                        $manualFinalPrice = round($originalPrice - $manualDiscountAmount, 2);
                                    }
                                    
                                    // Override jika perhitungan model tidak benar
                                    if ($finalPrice == $originalPrice || $discountAmount == 0) {
                                        $discountAmount = $manualDiscountAmount;
                                        $finalPrice = $manualFinalPrice;
                                    }
                                } elseif ($activePromotion->discount_type === 'fixed') {
                                    $manualDiscountAmount = $activePromotion->discount_value;
                                    $manualFinalPrice = max(0, $originalPrice - $manualDiscountAmount);
                                    
                                    if ($finalPrice == $originalPrice || $discountAmount == 0) {
                                        $discountAmount = $manualDiscountAmount;
                                        $finalPrice = $manualFinalPrice;
                                    }
                                }
                            }
                        }
                    @endphp
                    <div class="price-section mb-4">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="text-muted text-decoration-line-through fs-6">
                                Rp {{ number_format($originalPrice, 0, ',', '.') }}
                            </span>
                            <span class="badge bg-success text-white rounded-pill px-2 py-1 small">
                                {{ round((($originalPrice - $finalPrice) / $originalPrice) * 100) }}% OFF
                            </span>
                        </div>
                        <h4 class="fw-bold text-danger mb-1">
                            Rp {{ number_format($finalPrice, 0, ',', '.') }}
                        </h4>
                        <small class="text-success fw-semibold">
                            <i class="ti ti-circle-check me-1"></i>Hemat Rp {{ number_format($discountAmount, 0, ',', '.') }}
                        </small>
                    </div>
                @else
                    <div class="price-section mb-4">
                        <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                        <small class="text-muted">Harga terbaik untuk kualitas premium</small>
                    </div>
                @endif
                
                {{-- Action Buttons --}}
                <div class="mt-auto">
                    @php
                        $displayPrice = $hasDiscount ? $product->getFinalPrice(1) : (float) $product->price;
                        $originalPrice = (float) $product->price;
                        $displayPriceFormatted = number_format($displayPrice, 2, '.', '');
                        $originalPriceFormatted = number_format($originalPrice, 2, '.', '');
                    @endphp
                    
                    <div class="d-grid gap-2">
                        @if($product->stock > 0)
                            <button type="button" class="btn btn-primary btn-lg fw-semibold btn-checkout" 
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" 
                                    data-stock="{{ $product->stock }}"
                                    data-price-numeric="{{ $displayPriceFormatted }}"
                                    data-original-price="{{ $originalPriceFormatted }}"
                                    data-has-discount="{{ $hasDiscount ? 'true' : 'false' }}"
                                    data-image="{{ $product->image ? asset('storage/products/'.$product->image) : asset('assets/images/no-image.png') }}">
                                <i class="ti ti-shopping-cart me-2"></i>Pesan Sekarang
                            </button>
                        @else
                            <button class="btn btn-secondary btn-lg fw-semibold" disabled>
                                <i class="ti ti-ban me-2"></i>Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="ti ti-package-off text-muted" style="font-size: 5rem;"></i>
                </div>
                <h4 class="text-muted mb-2">Tidak Ada Produk Ditemukan</h4>
                <p class="text-muted mb-4">Maaf, tidak ada produk yang sesuai dengan pencarian Anda.</p>
                <button class="btn btn-primary" onclick="resetFilters()">
                    <i class="ti ti-refresh me-2"></i>Reset Filter
                </button>
            </div>
        </div>
        @endforelse
    </div>

    {{-- List View --}}
    <div id="listContainer" class="d-none">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light sticky-top">
                    <tr>
                        <th style="width: 40%">NAMA PRODUK</th>
                        <th style="width: 15%">KATEGORI</th>
                        <th style="width: 10%">STOK</th>
                        <th style="width: 10%">DISKON</th>
                        <th style="width: 15%" class="text-end">HARGA</th>
                        <th style="width: 10%" class="text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody id="listTableBody">
                    @forelse($products as $product)
                    @php
                        $hasDiscount = $product->hasActiveDiscount();
                        $activePromotion = $product->getActivePromotion();
                    @endphp
                    <tr class="product-row" data-category="{{ Str::slug($product->category->name ?? '') }}" data-name="{{ strtolower($product->name) }}" data-price="{{ $product->price }}">
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    @if($hasDiscount)
                                        <small class="text-success">
                                            <i class="ti ti-discount-2 me-1"></i>
                                            @if($activePromotion->discount_type === 'percentage')
                                                {{ $activePromotion->discount_value }}% OFF
                                            @else
                                                Diskon Rp {{ number_format($activePromotion->discount_value, 0, ',', '.') }}
                                            @endif
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark px-2 py-1">
                                {{ $product->category->name ?? 'Umum' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning text-dark' : 'bg-danger') }} px-2 py-1">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td>
                            @if($hasDiscount)
                                @php
                                    $originalPrice = (float) $product->price;
                                    $finalPrice = $product->getFinalPrice(1);
                                    $discountPercent = round((($originalPrice - $finalPrice) / $originalPrice) * 100);
                                @endphp
                                <span class="badge bg-danger text-white px-2 py-1">
                                    {{ $discountPercent }}%
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($hasDiscount)
                                @php
                                    $originalPrice = (float) $product->price;
                                    $finalPrice = $product->getFinalPrice(1);
                                @endphp
                                <div class="text-muted text-decoration-line-through small">
                                    Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                </div>
                                <div class="fw-bold text-danger">
                                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                </div>
                            @else
                                <div class="fw-bold text-primary">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($product->stock > 0)
                                <button type="button" class="btn btn-primary btn-sm btn-checkout" 
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}" 
                                        data-stock="{{ $product->stock }}"
                                        data-price-numeric="{{ $hasDiscount ? $product->getFinalPrice(1) : $product->price }}"
                                        data-original-price="{{ $product->price }}"
                                        data-has-discount="{{ $hasDiscount ? 'true' : 'false' }}"
                                        data-image="{{ $product->image ? asset('storage/products/'.$product->image) : asset('assets/images/no-image.png') }}">
                                    <i class="ti ti-shopping-cart me-1"></i>Pesan
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="ti ti-ban me-1"></i>Habis
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="ti ti-package-off fs-1 mb-3"></i>
                                <h5>Tidak Ada Produk Ditemukan</h5>
                                <p>Maaf, tidak ada produk yang sesuai dengan pencarian Anda.</p>
                                <button class="btn btn-primary" onclick="resetFilters()">
                                    <i class="ti ti-refresh me-2"></i>Reset Filter
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
@if($products->hasPages())
<div class="row mt-5">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            <nav aria-label="Products pagination">
                {{ $products->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>
@endif

{{-- MODAL DETAIL --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalDetailLabel">Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row">
                <div class="col-md-5 text-center mb-3">
                    <img id="modalImage" src="" alt="Produk" class="img-fluid"
                        style="max-height:220px;object-fit:contain;">
                </div>
                <div class="col-md-7">
                    <h4 class="fw-bold" id="modalName"></h4>
                    <div class="mb-1 text-muted" id="modalCategory"></div>
                    <div class="mb-3" id="modalDescription"></div>
                    <div id="modalDiscountBadge" class="mb-2 d-none">
                        <span class="badge bg-danger text-white px-3 py-2">
                            <i class="ti ti-discount-2 me-1"></i>
                            <span id="modalDiscountText"></span>
                        </span>
                    </div>
                    <div id="modalPriceContainer">
                        <div id="modalOriginalPrice" class="text-muted text-decoration-line-through small d-none"></div>
                        <h3 class="fw-bold text-success mb-0" id="modalPrice"></h3>
                        <small id="modalSavings" class="text-success fw-bold d-none"></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CHECKOUT --}}
<div class="modal fade" id="modalCheckout" tabindex="-1" aria-labelledby="modalCheckoutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formCheckout" autocomplete="off">
            @csrf
            <input type="hidden" name="product_id" id="checkoutProductId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalCheckoutLabel">Checkout Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="checkoutImage" src="" alt="Produk" class="img-fluid"
                            style="max-height:120px;object-fit:contain;">
                    </div>
                    <h5 class="fw-bold mb-1" id="checkoutName"></h5>
                    <div class="mb-2 text-muted">Stok tersedia: <span id="checkoutStock"></span></div>
                    <div id="checkoutDiscountInfo" class="d-none mb-2">
                        <div class="text-muted text-decoration-line-through small">
                            Harga Normal: Rp <span id="checkoutOriginalPrice"></span>
                        </div>
                        <div class="text-success fw-bold small">
                            <i class="ti ti-discount-2 me-1"></i>Diskon: Rp <span id="checkoutDiscountAmount"></span>
                        </div>
                    </div>
                    <div class="mb-2 fw-bold text-success">Harga: Rp <span id="checkoutPrice"></span></div>
                    <div class="mb-3">
                        <label for="qty" class="form-label">Jumlah Beli</label>
                        <input type="number" class="form-control" min="1" id="checkoutQty" name="qty" value="1"
                            required>
                    </div>
                    <div class="mb-2">
                        <h5>Total: <span class="fw-bold text-success" id="checkoutTotal">Rp 0</span></h5>
                    </div>
                    <div id="checkoutAlert" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Checkout</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
/* Product Card Animations */
.product-card {
    transition: all 0.3s ease;
    border-radius: 16px !important;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.product-image {
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

/* Image Overlay */
.image-overlay {
    background: rgba(0,0,0,0.5);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .image-overlay {
    opacity: 1;
}

.quick-view-btn {
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.product-card:hover .quick-view-btn {
    transform: translateY(0);
}

/* Discount Badge */
.discount-badge {
    background: linear-gradient(135deg, #ff6b6b, #ee5a5a) !important;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(238, 90, 90, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(238, 90, 90, 0); }
    100% { box-shadow: 0 0 0 0 rgba(238, 90, 90, 0); }
}

/* Search and Filter Styling */
.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Loading Animation */
.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Product Name Hover */
.product-name:hover {
    color: #667eea;
    transition: color 0.3s ease;
}

/* Custom z-index */
.z-index-10 {
    z-index: 10;
}

/* View Toggle Buttons */
.view-toggle .btn.active {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
}

/* List View Styles */
.list-view .product-item {
    margin-bottom: 0.25rem;
}

.list-view .product-card {
    border-radius: 6px;
    transition: none;
    transform: none !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
    border: 1px solid #e9ecef;
}

.list-view .product-card:hover {
    transform: none !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    border-color: #667eea;
}

.list-view .card-body {
    padding: 0.75rem 1rem;
}

.list-view .product-image-container {
    display: none;
}

.list-view .image-overlay {
    display: none;
}

.list-view .grid-view-content {
    display: none;
}

.list-view .product-info {
    display: block !important;
    width: 100%;
}

.list-view .product-info .row {
    margin: 0;
}

.list-view .product-info .col-1,
.list-view .product-info .col-2,
.list-view .product-info .col-4 {
    padding: 0 0.5rem;
}

/* Table header styling */
#listViewHeader {
    position: sticky;
    top: 0;
    z-index: 5;
}

#listViewHeader .card {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

/* Grid View Styles */
.grid-view-content {
    display: block;
}

.product-info {
    display: none;
}

/* Fade Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-item {
    animation: fadeInUp 0.6s ease forwards;
}

/* Stagger animation delay */
.product-item:nth-child(1) { animation-delay: 0.1s; }
.product-item:nth-child(2) { animation-delay: 0.2s; }
.product-item:nth-child(3) { animation-delay: 0.3s; }
.product-item:nth-child(4) { animation-delay: 0.4s; }
.product-item:nth-child(5) { animation-delay: 0.5s; }
.product-item:nth-child(6) { animation-delay: 0.6s; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search and Filter Functionality
    const searchInput = document.getElementById('searchProducts');
    const categoryFilter = document.getElementById('filterCategory');
    const sortSelect = document.getElementById('sortProducts');
    const gridViewBtn = document.getElementById('gridView');
    const listViewBtn = document.getElementById('listView');
    const productsContainer = document.getElementById('productsContainer');
    const gridContainer = document.getElementById('gridContainer');
    const listContainer = document.getElementById('listContainer');
    const productItems = document.querySelectorAll('.product-item');
    const productRows = document.querySelectorAll('.product-row');

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterProducts();
        });
    }

    // Category filter
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            filterProducts();
        });
    }

    // Sort functionality
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortProducts();
        });
    }

    // View toggle
    if (gridViewBtn && listViewBtn) {
        gridViewBtn.addEventListener('click', function() {
            setGridView();
        });

        listViewBtn.addEventListener('click', function() {
            setListView();
        });
    }

    function filterProducts() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const selectedCategory = categoryFilter ? categoryFilter.value : '';
        let visibleCount = 0;

        // Filter grid items
        productItems.forEach(item => {
            const productName = item.getAttribute('data-name');
            const productCategory = item.getAttribute('data-category');
            
            const matchesSearch = !searchTerm || productName.includes(searchTerm);
            const matchesCategory = !selectedCategory || productCategory === selectedCategory;
            
            if (matchesSearch && matchesCategory) {
                item.style.display = 'block';
                item.style.animation = 'fadeInUp 0.6s ease forwards';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Filter list rows
        productRows.forEach(row => {
            const productName = row.getAttribute('data-name');
            const productCategory = row.getAttribute('data-category');
            
            const matchesSearch = !searchTerm || productName.includes(searchTerm);
            const matchesCategory = !selectedCategory || productCategory === selectedCategory;
            
            if (matchesSearch && matchesCategory) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });

        updateProductCount(visibleCount);
    }

    function sortProducts() {
        const sortBy = sortSelect.value;
        const productsArray = Array.from(productItems);
        
        productsArray.sort((a, b) => {
            switch(sortBy) {
                case 'price-low':
                    return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
                case 'price-high':
                    return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
                case 'name':
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                case 'newest':
                default:
                    return 0; // Keep original order
            }
        });

        // Re-append sorted items
        productsArray.forEach(item => {
            productsContainer.appendChild(item);
        });

        // Re-apply stagger animation
        productsArray.forEach((item, index) => {
            if (item.style.display !== 'none') {
                item.style.animationDelay = `${(index % 6) * 0.1}s`;
            }
        });
    }

    function setGridView() {
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        
        // Show grid container, hide list container
        if (gridContainer) {
            gridContainer.classList.remove('d-none');
        }
        if (listContainer) {
            listContainer.classList.add('d-none');
        }
    }

    function setListView() {
        listViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
        
        // Hide grid container, show list container
        if (gridContainer) {
            gridContainer.classList.add('d-none');
        }
        if (listContainer) {
            listContainer.classList.remove('d-none');
        }
    }

    function updateProductCount(count) {
        const countElement = document.getElementById('productCount');
        if (countElement) {
            countElement.textContent = count;
        }
    }

    // Reset filters function
    window.resetFilters = function() {
        if (searchInput) searchInput.value = '';
        if (categoryFilter) categoryFilter.value = '';
        if (sortSelect) sortSelect.value = 'newest';
        
        productItems.forEach(item => {
            item.style.display = 'block';
        });
        
        productRows.forEach(row => {
            row.style.display = 'table-row';
        });
        
        updateProductCount(productItems.length);
    };

    // MODAL DETAIL
    document.querySelectorAll('.btn-show-detail').forEach(btn => {
      btn.addEventListener('click', function(e){
        e.preventDefault();
        document.getElementById('modalName').innerText = btn.dataset.name;
        document.getElementById('modalCategory').innerText = btn.dataset.category;
        document.getElementById('modalDescription').innerText = btn.dataset.description;
        document.getElementById('modalPrice').innerText = btn.dataset.price;
        document.getElementById('modalImage').src = btn.dataset.image;
        
        // Handle discount display
        const hasDiscount = btn.dataset.hasDiscount === 'true';
        const discountBadge = document.getElementById('modalDiscountBadge');
        const originalPriceEl = document.getElementById('modalOriginalPrice');
        const savingsEl = document.getElementById('modalSavings');
        
        if (hasDiscount) {
          const originalPrice = parseFloat(btn.dataset.originalPrice);
          const finalPrice = parseFloat(btn.dataset.priceNumeric);
          
          discountBadge.classList.remove('d-none');
          document.getElementById('modalDiscountText').innerText = btn.dataset.discountText;
          
          originalPriceEl.classList.remove('d-none');
          originalPriceEl.innerText = 'Rp ' + originalPrice.toLocaleString('id-ID');
          
          savingsEl.classList.remove('d-none');
          savingsEl.innerText = 'Hemat Rp ' + (originalPrice - finalPrice).toLocaleString('id-ID');
          
          document.getElementById('modalPrice').className = 'fw-bold text-danger mb-0';
        } else {
          discountBadge.classList.add('d-none');
          originalPriceEl.classList.add('d-none');
          savingsEl.classList.add('d-none');
          document.getElementById('modalPrice').className = 'fw-bold text-success mb-0';
        }
        
        new bootstrap.Modal(document.getElementById('modalDetail')).show();
      });
    });

    // MODAL CHECKOUT
    let checkoutModal = new bootstrap.Modal(document.getElementById('modalCheckout'));
    document.querySelectorAll('.btn-checkout').forEach(btn => {
      btn.addEventListener('click', function(e){
        e.preventDefault();
        let id = btn.dataset.id;
        let name = btn.dataset.name;
        let stock = parseInt(btn.dataset.stock);
        let price = parseFloat(btn.dataset.priceNumeric);
        let originalPrice = parseFloat(btn.dataset.originalPrice);
        let hasDiscount = btn.dataset.hasDiscount === 'true';
        let img = btn.dataset.image;
        let priceNumeric = parseFloat(btn.dataset.priceNumeric);

        document.getElementById('checkoutProductId').value = id;
        document.getElementById('checkoutName').innerText = name;
        document.getElementById('checkoutStock').innerText = stock;
        document.getElementById('checkoutPrice').innerText = Math.round(priceNumeric).toLocaleString('id-ID');
        document.getElementById('checkoutQty').value = 1;
        document.getElementById('checkoutImage').src = img;
        
        // Handle discount display in checkout
        const discountInfo = document.getElementById('checkoutDiscountInfo');
        if (hasDiscount && originalPrice > priceNumeric) {
          discountInfo.classList.remove('d-none');
          document.getElementById('checkoutOriginalPrice').innerText = Math.round(originalPrice).toLocaleString('id-ID');
          document.getElementById('checkoutDiscountAmount').innerText = Math.round(originalPrice - priceNumeric).toLocaleString('id-ID');
        } else {
          discountInfo.classList.add('d-none');
        }
        
        updateCheckoutTotal(priceNumeric, 1, hasDiscount, originalPrice);

        // Reset alert
        document.getElementById('checkoutAlert').classList.add('d-none');

        // Validasi saat input jumlah
        document.getElementById('checkoutQty').oninput = function() {
          let qty = parseInt(this.value) || 1;
          if(qty > stock) {
            this.value = stock;
            qty = stock;
          }
          if(qty < 1) {
            this.value = 1;
            qty = 1;
          }
          updateCheckoutTotal(priceNumeric, qty, hasDiscount, originalPrice);
        };

        checkoutModal.show();
      });
    });

    function updateCheckoutTotal(price, qty, hasDiscount = false, originalPrice = 0) {
      let total = price * qty;
      document.getElementById('checkoutTotal').innerText = 'Rp ' + Math.round(total).toLocaleString('id-ID');
      
      // Update discount info for quantity changes
      if (hasDiscount && originalPrice > price) {
        const discountPerUnit = originalPrice - price;
        const totalDiscount = discountPerUnit * qty;
        document.getElementById('checkoutDiscountAmount').innerText = Math.round(totalDiscount).toLocaleString('id-ID');
      }
    }

    // SUBMIT FORM CHECKOUT
    document.getElementById('formCheckout').onsubmit = function(e){
      e.preventDefault();
      let btn = this.querySelector('button[type="submit"]');
      btn.disabled = true;
      let formData = new FormData(this);
      fetch('{{ route("customer.orders.store") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': formData.get('_token')
        },
        body: formData
      })
      .then(response => response.json())
      .then(res => {
        btn.disabled = false;
        if(res.success){
          checkoutModal.hide();
          // SweetAlert2 notifikasi (jika pakai SweetAlert2)
          Swal.fire('Berhasil!', res.message, 'success').then(() => {
            window.location.reload();
          });
        }else{
          showCheckoutError(res.message || 'Gagal melakukan checkout!');
        }
      })
      .catch(() => {
        btn.disabled = false;
        showCheckoutError('Gagal terhubung ke server!');
      });
    };

    function showCheckoutError(msg){
      let alert = document.getElementById('checkoutAlert');
      alert.innerText = msg;
      alert.classList.remove('d-none');
    }

    // Loading animation for buttons
    function addLoadingToButton(button, text = 'Processing...') {
        button.disabled = true;
        button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            ${text}
        `;
    }

    function removeLoadingFromButton(button, originalText) {
        button.disabled = false;
        button.innerHTML = originalText;
    }

    // Smooth scroll to products after filter
    function scrollToProducts() {
        const productsSection = document.getElementById('productsContainer');
        if (productsSection) {
            productsSection.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    }
});
</script>
@endpush

@endsection