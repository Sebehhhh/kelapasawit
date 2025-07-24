<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kategori Sawit Lokal</title>
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
        <h2>LAPORAN KATEGORI SAWIT LOKAL</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
        @if($category)
            <p>Kategori: {{ $category->name }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Produk</th>
                <th width="15%">Kategori</th>
                <th width="15%">Harga</th>
                <th width="10%">Stok</th>
                <th width="15%">Total Nilai</th>
                <th width="15%">Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalProducts = 0;
                $totalStock = 0;
                $totalValue = 0;
            @endphp
            @forelse($products as $index => $product)
                @php
                    $totalProducts++;
                    $totalStock += $product->stock;
                    $productValue = $product->price * $product->stock;
                    $totalValue += $productValue;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $product->stock }}</td>
                    <td class="text-right">Rp {{ number_format($productValue, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $product->created_at ? $product->created_at->format('d/m/Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data produk sawit lokal</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <table style="width: 50%; margin-left: auto;">
            <tr>
                <td><strong>Total Produk:</strong></td>
                <td class="text-right"><strong>{{ $totalProducts }} item</strong></td>
            </tr>
            <tr>
                <td><strong>Total Stok:</strong></td>
                <td class="text-right"><strong>{{ $totalStock }} unit</strong></td>
            </tr>
            <tr>
                <td><strong>Total Nilai Inventori:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalValue, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh sistem pada {{ date('d/m/Y H:i:s') }}</p>
        <p>Kelapa Sawit UKM - Sistem Manajemen Inventori</p>
    </div>
</body>
</html>