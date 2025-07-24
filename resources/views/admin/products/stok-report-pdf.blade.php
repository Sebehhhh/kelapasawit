<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Barang</title>
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
        .status-rendah { background: #ffebee; color: #c62828; }
        .status-normal { background: #e8f5e8; color: #2e7d2e; }
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
        <h2>LAPORAN STOK BARANG</h2>
        <p>CV. Kelapa Sawit Unggul</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Kategori</th>
                <th width="30%">Nama Produk</th>
                <th width="15%">Harga Satuan</th>
                <th width="10%">Stok Saat Ini</th>
                <th width="10%">Status</th>
                <th width="10%">Nilai Stok</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalStok = 0; 
                $totalNilai = 0;
                $stokRendah = 0;
            @endphp
            @foreach($products as $product)
            @php 
                $nilaiStok = $product->stock * $product->price;
                $totalStok += $product->stock;
                $totalNilai += $nilaiStok;
                $statusStok = $product->stock <= 10 ? 'Rendah' : 'Normal';
                if($product->stock <= 10) $stokRendah++;
            @endphp
            <tr class="{{ $product->stock <= 10 ? 'status-rendah' : 'status-normal' }}">
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>{{ $product->name }}</td>
                <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="text-center">{{ $product->stock }} pcs</td>
                <td class="text-center">{{ $statusStok }}</td>
                <td class="text-right">Rp {{ number_format($nilaiStok, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-center">{{ $totalStok }} pcs</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }} WIB</p>
        <p>Total {{ count($products) }} produk | {{ $stokRendah }} produk stok rendah</p>
    </div>
</body>
</html> 