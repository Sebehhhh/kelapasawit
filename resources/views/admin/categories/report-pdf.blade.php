<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kategori Sawit</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #e0e0e0; }
        h2 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Laporan Kategori Sawit</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Jumlah Produk</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $cat->name }}</td>
                <td>{{ $cat->description }}</td>
                <td>{{ $cat->products_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 