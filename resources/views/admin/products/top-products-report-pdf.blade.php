<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Produk Terlaris</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #e0e0e0; }
        h2 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Laporan Produk Terlaris</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Total Terjual</th>
                <th>Harga Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>{{ $product->total_terjual ?? 0 }}</td>
                <td>Rp {{ number_format($product->price,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 