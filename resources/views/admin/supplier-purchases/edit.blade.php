@extends('admin.layouts.app')
@section('title', 'Edit Pembelian Pemasok')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">Edit Pembelian dari Pemasok</div>
                <div class="card-body">
                    <form action="{{ route('admin.supplier-purchases.update', $purchase) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label>Nama Supplier</label>
                            <input type="text" name="supplier_name" class="form-control" value="{{ $purchase->supplier_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @if($purchase->category_id == $cat->id) selected @endif>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Produk</label>
                            <select name="product_id" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}" @if($purchase->product_id == $prod->id) selected @endif>{{ $prod->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Harga Satuan</label>
                            <input type="number" name="unit_price" class="form-control" min="0" value="{{ $purchase->unit_price }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Jumlah</label>
                            <input type="number" name="quantity" class="form-control" min="1" value="{{ $purchase->quantity }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal Pembelian</label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ $purchase->purchase_date }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Catatan</label>
                            <textarea name="note" class="form-control">{{ $purchase->note }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-warning">Update</button>
                        <a href="{{ route('admin.supplier-purchases.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 