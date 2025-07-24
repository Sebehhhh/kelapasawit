@extends('layouts.app')
@section('title', 'Difiturkan')
@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Difiturkan</h4>
        <div>
            <a href="{{ route('owner.promotions.printReport', request()->all()) }}" target="_blank" class="btn btn-success me-2">
                <i class="ti ti-printer"></i> Cetak Laporan
            </a>
        </div>
    </div>
</div>
<!-- FILTER PROMOSI -->
<div class="card border-0 shadow mb-4">
    <div class="card-body pb-2">
        <form class="row g-2 align-items-end" method="GET" action="">
            <div class="col-md-4">
                <label for="filter_judul" class="form-label mb-1">Judul</label>
                <input type="text" name="judul" id="filter_judul" class="form-control" placeholder="Cari judul promosi..." value="{{ request('judul') }}">
            </div>
            <div class="col-md-2 d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-success">Filter</button>
                <a href="{{ route('owner.promotions.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promotion)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $promotion->title ?? $promotion->judul }}</td>
                        <td>{{ $promotion->description ?? $promotion->deskripsi }}</td>
                        <td>{{ $promotion->start_date ?? $promotion->tanggal_mulai }}</td>
                        <td>{{ $promotion->end_date ?? $promotion->tanggal_selesai }}</td>
                        <td>{{ $promotion->status ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada promosi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $promotions->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection 