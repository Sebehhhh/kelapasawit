<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Struk Keluar Penjualan</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #333; 
            padding: 8px; 
            text-align: left; 
            font-size: 11px;
        }
        th { 
            background: #f5f5f5; 
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row {
            background: #f9f9f9;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN STRUK KELUAR PENJUALAN</h2>
        <p>CV. Kelapa Sawit Unggul</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Order ID</th>
                <th width="20%">Customer</th>
                <th width="20%">Produk</th>
                <th width="8%">Qty</th>
                <th width="15%">Total</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKeseluruhan = 0; @endphp
            @foreach($orders as $order)
                @foreach($order->details as $detail)
                <tr>
                    <td class="text-center">{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $order->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">#{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td>{{ $detail->product->name ?? '-' }}</td>
                    <td class="text-center">{{ $detail->quantity }} pcs</td>
                    <td class="text-right">Rp {{ number_format($detail->price * $detail->quantity,0,',','.') }}</td>
                    <td class="text-center">{{ ucfirst($order->status) }}</td>
                </tr>
                @php $totalKeseluruhan += ($detail->price * $detail->quantity); @endphp
                @endforeach
            @endforeach
            <tr class="total-row">
                <td colspan="6" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-right">Rp {{ number_format($totalKeseluruhan,0,',','.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }} WIB</p>
        <p>Total {{ count($orders) }} transaksi penjualan</p>
    </div>
</body>
</html> 