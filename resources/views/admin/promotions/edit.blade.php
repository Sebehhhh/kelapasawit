@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Promosi</h4>
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Judul</label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title', $promotion->title) }}">
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control">{{ old('description', $promotion->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label>Produk</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">- Pilih Produk -</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id', $promotion->product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 row">
                    <div class="col">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" required value="{{ old('start_date', $promotion->start_date) }}">
                    </div>
                    <div class="col">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" required value="{{ old('end_date', $promotion->end_date) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Gambar</label>
                    @if($promotion->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/promotions/'.$promotion->image) }}" alt="img" width="80">
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control">
                </div>
                <button class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection 