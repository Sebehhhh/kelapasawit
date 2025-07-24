<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian dari Pemasok</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #e0e0e0; }
        h2 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Laporan Pembelian dari Pemasok</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Supplier</th>
                <th>Kategori</th>
                <th>Produk</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $purchase->supplier_name }}</td>
                <td>{{ $purchase->category->name ?? '-' }}</td>
                <td>{{ $purchase->product->name ?? '-' }}</td>
                <td>Rp {{ number_format($purchase->unit_price,0,',','.') }}</td>
                <td>{{ $purchase->quantity }}</td>
                <td>Rp {{ number_format($purchase->total_price,0,',','.') }}</td>
                <td>{{ $purchase->purchase_date }}</td>
                <td>{{ $purchase->note }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 