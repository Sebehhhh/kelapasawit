@extends('layouts.app')
@section('title', 'Testimoni Saya')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-0">Testimoni Saya</h4>
        <span class="text-muted">Daftar testimoni yang sudah Anda berikan.</span>
    </div>
</div>
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                        <th>Status Order</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testi)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $testi->orderDetail && $testi->orderDetail->product ? $testi->orderDetail->product->name : '-' }}</td>
                        <td>
                            @for($i=1;$i<=5;$i++)
                                <i class="ti ti-star" style="color:{{ $i <= $testi->rating ? '#ffc107' : '#ccc' }};"></i>
                            @endfor
                        </td>
                        <td>{{ $testi->message }}</td>
                        <td>{{ $testi->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $testi->orderDetail && $testi->orderDetail->order ? ucfirst($testi->orderDetail->order->status) : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada testimoni.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $testimonials->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection 