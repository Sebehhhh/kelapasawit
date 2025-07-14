@extends('layouts.app')
@section('title', 'Monitoring Produk Bibit Sawit')
@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold mb-0">Monitoring Produk</h4>
        <div>
            <a href="{{ route('owner.products.printReport', request()->all()) }}" target="_blank" class="btn btn-success me-2">
                <i class="ti ti-printer"></i> Cetak PDF
            </a>
        </div>
    </div>
</div>
<!-- FILTER PRODUK -->
<div class="card border-0 shadow mb-4">
    <div class="card-body pb-2">
        <form class="row g-2 align-items-end" method="GET" action="">
            <div class="col-md-3">
                <label for="filter_category" class="form-label mb-1">Kategori</label>
                <select name="category_id" id="filter_category" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @if(isset($selectedCategory) && $selectedCategory == $cat->id) selected @endif>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter_name" class="form-label mb-1">Nama Produk</label>
                <input type="text" name="name" id="filter_name" class="form-control" placeholder="Cari nama produk..." value="{{ $searchName ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="sort_by" class="form-label mb-1">Urutkan</label>
                <select name="sort_by" id="sort_by" class="form-select">
                    <option value="">Tanggal Terbaru</option>
                    <option value="price" @if(isset($sortBy) && $sortBy=='price') selected @endif>Harga</option>
                    <option value="stock" @if(isset($sortBy) && $sortBy=='stock') selected @endif>Stok</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="sort_order" class="form-label mb-1">Urutan</label>
                <select name="sort_order" id="sort_order" class="form-select">
                    <option value="desc" @if(isset($sortOrder) && $sortOrder=='desc') selected @endif>Terbesar</option>
                    <option value="asc" @if(isset($sortOrder) && $sortOrder=='asc') selected @endif>Terkecil</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-success mt-3">Filter</button>
                <a href="{{ route('owner.products.index') }}" class="btn btn-secondary mt-3">Reset</a>
            </div>
        </form>
    </div>
</div>
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kategori</th>
                        <th>Nama Produk</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                        <th class="text-center" style="width:100px;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->category->name ?? $product->category->nama ?? '-' }}</td>
                        <td>{{ $product->name ?? $product->nama }}</td>
                        <td>{{ $product->description ?? $product->deskripsi ?? '-' }}</td>
                        <td>Rp {{ number_format($product->price ?? $product->harga, 0, ',', '.') }}</td>
                        <td>{{ $product->stock ?? $product->stok }}</td>
                        <td>
                            @if($product->image)
                            <img src="{{ asset('storage/products/' . $product->image) }}" alt="Foto" width="60">
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $product->id }}">
                                <i class="ti ti-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
<!-- Modal Detail Produk (letakkan di luar table agar struktur HTML rapi) -->
@foreach($products as $product)
<div class="modal fade" id="detailModal{{ $product->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $product->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel{{ $product->id }}">Detail Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4 text-center mb-3">
            @if($product->image)
              <img src="{{ asset('storage/products/' . $product->image) }}" alt="Foto Produk" class="img-fluid rounded" style="max-height:200px;">
            @else
              <span class="text-muted small">Tidak ada gambar</span>
            @endif
          </div>
          <div class="col-md-8">
            <table class="table table-borderless">
              <tr><th>Nama Produk</th><td>{{ $product->name ?? $product->nama }}</td></tr>
              <tr><th>Kategori</th><td>{{ $product->category->name ?? $product->category->nama ?? '-' }}</td></tr>
              <tr><th>Harga</th><td>Rp{{ number_format($product->price ?? $product->harga,0,',','.') }}</td></tr>
              <tr><th>Stok</th><td>{{ $product->stock ?? $product->stok }}</td></tr>
              <tr><th>Status</th><td>{{ $product->status ?? '-' }}</td></tr>
              <tr><th>Deskripsi</th><td>{{ $product->description ?? $product->deskripsi ?? '-' }}</td></tr>
              <tr><th>Tanggal Input</th><td>{{ $product->created_at ? $product->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endforeach
@endsection 