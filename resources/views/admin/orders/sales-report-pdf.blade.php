<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Penjualan</title>
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
        .summary-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN HASIL PENJUALAN</h2>
        <p>CV. Kelapa Sawit Unggul</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary-box">
        @php 
            $totalTransaksi = collect($orders)->sum('jumlah');
            $totalPenjualan = collect($orders)->sum('total');
            $rataRata = $totalTransaksi > 0 ? $totalPenjualan / $totalTransaksi : 0;
        @endphp
        <div class="summary-item">
            <strong>Total Hari:</strong> {{ count($orders) }} hari
        </div>
        <div class="summary-item">
            <strong>Total Transaksi:</strong> {{ $totalTransaksi }} transaksi
        </div>
        <div class="summary-item">
            <strong>Rata-rata per Transaksi:</strong> Rp {{ number_format($rataRata, 0, ',', '.') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%">No</th>
                <th width="20%">Tanggal</th>
                <th width="15%">Jumlah Transaksi</th>
                <th width="20%">Total Penjualan</th>
                <th width="20%">Rata-rata per Transaksi</th>
                <th width="17%">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            @php
                $rataPerTransaksi = $order->jumlah > 0 ? $order->total / $order->jumlah : 0;
                $persentase = $totalPenjualan > 0 ? ($order->total / $totalPenjualan) * 100 : 0;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($order->tanggal)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $order->jumlah }} transaksi</td>
                <td class="text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($rataPerTransaksi, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($persentase, 1) }}%</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-center">{{ $totalTransaksi }} transaksi</td>
                <td class="text-right">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($rataRata, 0, ',', '.') }}</td>
                <td class="text-center">100.0%</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }} WIB</p>
        <p>Periode: {{ count($orders) }} hari | Total omzet: Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
    </div>
</body>
</html> 