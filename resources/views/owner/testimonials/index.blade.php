@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Monitoring Testimoni</h4>
        <form class="d-flex" method="GET" action="">
            <input type="text" name="user" class="form-control me-2" placeholder="Cari User" value="{{ request('user') }}">
            <select name="rating_sort" class="form-control me-2" style="max-width:160px">
                <option value="">Urutkan Rating</option>
                <option value="asc" {{ request('rating_sort') == 'asc' ? 'selected' : '' }}>Rating Terendah</option>
                <option value="desc" {{ request('rating_sort') == 'desc' ? 'selected' : '' }}>Rating Tertinggi</option>
            </select>
            <button type="submit" class="btn btn-primary ms-2">Filter</button>
        </form>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Produk</th>
                            <th>Pesan</th>
                            <th>Rating</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testimonials as $testimonial)
                        <tr>
                            <td>{{ $loop->iteration + ($testimonials->firstItem() ? $testimonials->firstItem() - 1 : 0) }}</td>
                            <td>{{ $testimonial->user->name ?? '-' }}</td>
                            <td>{{ $testimonial->product->name ?? '-' }}</td>
                            <td>{{ $testimonial->message }}</td>
                            <td>{{ $testimonial->rating }}</td>
                            <td>{{ $testimonial->created_at->format('d-m-Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada testimoni.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $testimonials->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 