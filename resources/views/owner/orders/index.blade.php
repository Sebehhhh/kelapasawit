@extends('layouts.app')
@section('title', 'Monitoring Order')
@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold mb-0">Monitoring Order</h4>
        <div>
            <a href="{{ route('owner.orders.printReport', request()->all()) }}" target="_blank" class="btn btn-success me-2">
                <i class="ti ti-printer"></i> Cetak Laporan
            </a>
        </div>
    </div>
</div>
<!-- FILTER ORDER -->
<div class="card border-0 shadow mb-4">
    <div class="card-body pb-2">
        <form class="row g-2 align-items-end" method="GET" action="">
            <div class="col-md-3">
                <label for="filter_tanggal_mulai" class="form-label mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" id="filter_tanggal_mulai" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="filter_tanggal_selesai" class="form-label mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" id="filter_tanggal_selesai" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label for="filter_status" class="form-label mb-1">Status</label>
                <select name="status" id="filter_status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter_user" class="form-label mb-1">User</label>
                <input type="text" name="user_name" id="filter_user" class="form-control" placeholder="Cari nama user..." value="{{ request('user_name') }}">
            </div>
            <div class="col-md-2 d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-success">Filter</button>
                <a href="{{ route('owner.orders.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th class="text-center" style="width:100px;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->created_at ? $order->created_at->format('d-m-Y') : '-' }}</td>
                        <td>{{ $order->user->name ?? '-' }}</td>
                        <td>{{ $order->status }}</td>
                        <td>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->id }}">
                                <i class="ti ti-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada order.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $orders->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
<!-- Modal Detail Order (letakkan di luar table agar struktur HTML rapi) -->
@foreach($orders as $order)
<div class="modal fade" id="detailModal{{ $order->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $order->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel{{ $order->id }}">Detail Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr><th>Tanggal Order</th><td>{{ $order->created_at ? $order->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
              <tr><th>User</th><td>{{ $order->user->name ?? '-' }}</td></tr>
              <tr><th>Status</th><td>{{ $order->status }}</td></tr>
              <tr><th>Total</th><td>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td></tr>
            </table>
          </div>
          <div class="col-md-6">
            <h6>Detail Produk</h6>
            <ul class="list-group">
              @foreach($order->details as $detail)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $detail->product->name ?? '-' }}
                <span>x{{ $detail->quantity }}</span>
              </li>
              @endforeach
            </ul>
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