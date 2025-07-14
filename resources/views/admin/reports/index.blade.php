@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Laporan Penjualan</h4>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <form class="row g-3 align-items-end" method="get">
                <div class="col-md-3">
                    <label>Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $start }}">
                </div>
                <div class="col-md-3">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $end }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary">Filter</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary" disabled>Export (coming soon)</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="alert alert-info">Jumlah Order: <b>{{ $totalOrder }}</b></div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-success">Total Penjualan: <b>Rp{{ number_format($totalAmount,0,',','.') }}</b></div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td>{{ $order->order_date ? $order->order_date->format('d M Y') : '-' }}</td>
                            <td>Rp{{ number_format($order->total_amount,0,',','.') }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($order->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada data order.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 