@extends('layouts.app')
@section('title', 'Riwayat Pembayaran')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-0">Riwayat Pembayaran</h4>
        <span class="text-muted">Semua pembayaran yang pernah Anda lakukan.</span>
    </div>
</div>
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Order</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $i => $pay)
                    <tr>
                        <td>{{ $payments->firstItem() + $i }}</td>
                        <td>{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d/m/Y H:i') : '-' }}</td>
                        <td>#{{ $pay->order_id }}</td>
                        <td>Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</td>
                        <td>
                            @if($pay->paymentMethod)
                                @if($pay->paymentMethod->type == 'e-wallet')
                                    💳 {{ $pay->paymentMethod->name }}
                                @else
                                    🏦 {{ $pay->paymentMethod->name }}
                                @endif
                                <br><small class="text-muted">{{ $pay->paymentMethod->account_number }}</small>
                            @else
                                <span class="text-muted">Metode tidak tersedia</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge 
                                @if($pay->status == 'pending') bg-warning
                                @elseif($pay->status == 'accepted') bg-success
                                @elseif($pay->status == 'rejected') bg-danger
                                @else bg-secondary
                                @endif
                            ">
                                {{ ucfirst($pay->status) }}
                            </span>
                        </td>
                        <td>
                            @if($pay->proof_image)
                                <a href="{{ asset('storage/' . $pay->proof_image) }}" target="_blank">Lihat</a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada pembayaran.</td>
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