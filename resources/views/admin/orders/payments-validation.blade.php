@extends('layouts.app')
@section('title', 'Validasi Pembayaran')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-0">Validasi Pembayaran</h4>
        <span class="text-muted">Daftar pembayaran yang menunggu validasi admin.</span>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Order</th>
                        <th>User</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $i => $pay)
                    <tr>
                        <td>{{ $payments->firstItem() + $i }}</td>
                        <td>{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d/m/Y H:i') : '-' }}</td>
                        <td>#{{ $pay->order_id }}</td>
                        <td>{{ $pay->order->user->name ?? '-' }}</td>
                        <td>Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</td>
                        <td>{{ ucfirst(str_replace('_',' ',$pay->payment_method)) }}</td>
                        <td>
                            @if($pay->proof_image)
                                <a href="{{ asset('storage/' . $pay->proof_image) }}" target="_blank">Lihat</a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.payments.validate', $pay->id) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="action" value="accept">
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Terima pembayaran ini?')">Terima</button>
                            </form>
                            <form method="POST" action="{{ route('admin.payments.validate', $pay->id) }}" class="d-inline ms-1">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pembayaran ini?')">Tolak</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Tidak ada pembayaran menunggu validasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $payments->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection 