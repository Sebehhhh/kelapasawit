<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian dari Pemasok</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { height: 50px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary { margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/images/logos/logo.png') }}" alt="Logo" class="logo">
        <h2>LAPORAN PEMBELIAN DARI PEMASOK</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Supplier</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Kategori</th>
                <th width="20%">Nama Produk</th>
                <th width="8%">Qty</th>
                <th width="10%">Harga Satuan</th>
                <th width="10%">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPembelian = 0;
                $totalQty = 0;
            @endphp
            @forelse($invoices as $inv)
                @foreach($inv->details as $detail)
                    @php
                        $totalPembelian += $detail->subtotal;
                        $totalQty += $detail->quantity;
                    @endphp
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ $inv->details->count() }}" class="text-center">{{ $loop->parent->iteration }}</td>
                            <td rowspan="{{ $inv->details->count() }}">{{ $inv->supplier_name }}</td>
                            <td rowspan="{{ $inv->details->count() }}" class="text-center">
                                {{ \Carbon\Carbon::parse($inv->purchase_date)->format('d/m/Y') }}
                            </td>
                        @endif
                        <td>{{ $detail->product->category->name ?? '-' }}</td>
                        <td>{{ $detail->product->name ?? 'Produk tidak ditemukan' }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data pembelian</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <table style="width: 50%; margin-left: auto;">
            <tr>
                <td><strong>Total Transaksi:</strong></td>
                <td class="text-right"><strong>{{ $invoices->count() }} transaksi</strong></td>
            </tr>
            <tr>
                <td><strong>Total Quantity:</strong></td>
                <td class="text-right"><strong>{{ $totalQty }} unit</strong></td>
            </tr>
            <tr>
                <td><strong>Total Pembelian:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPembelian, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh sistem pada {{ date('d/m/Y H:i:s') }}</p>
        <p>Kelapa Sawit UKM - Sistem Manajemen Inventori</p>
    </div>
</body>
</html> 