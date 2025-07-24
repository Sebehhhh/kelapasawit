<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Retur Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #e0e0e0; }
        h2 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Laporan Retur Barang</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Retur</th>
                <th>Order</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Alasan</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returns as $retur)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $retur->return_date }}</td>
                <td>#{{ $retur->order_id }}</td>
                <td>{{ $retur->product->name ?? '-' }}</td>
                <td>{{ $retur->quantity }}</td>
                <td>{{ $retur->reason }}</td>
                <td>{{ ucfirst($retur->status) }}</td>
                <td>{{ $retur->note }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 