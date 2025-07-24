<!DOCTYPE html>
<html>
<head>
    <title>Laporan Produk Difiturkan</title>
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
        .status-active { color: green; font-weight: bold; }
        .status-expired { color: red; font-weight: bold; }
        .status-upcoming { color: orange; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/images/logos/logo.png') }}" alt="Logo" class="logo">
        <h2>LAPORAN PRODUK DIFITURKAN</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Judul Fitur</th>
                <th width="20%">Produk</th>
                <th width="12%">Tanggal Mulai</th>
                <th width="12%">Tanggal Selesai</th>
                <th width="10%">Status</th>
                <th width="21%">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPromotions = 0;
                $activePromotions = 0;
                $expiredPromotions = 0;
                $upcomingPromotions = 0;
            @endphp
            @forelse($promotions as $index => $promotion)
                @php
                    $now = now();
                    $status = '';
                    $statusClass = '';
                    
                    if ($promotion->start_date <= $now && $promotion->end_date >= $now) {
                        $status = 'Aktif';
                        $statusClass = 'status-active';
                    } elseif ($promotion->end_date < $now) {
                        $status = 'Berakhir';
                        $statusClass = 'status-expired';
                    } else {
                        $status = 'Akan Datang';
                        $statusClass = 'status-upcoming';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $promotion->title }}</td>
                    <td>{{ $promotion->product->name ?? '-' }}</td>
                    <td class="text-center">{{ $promotion->start_date ? \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $promotion->end_date ? \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y') : '-' }}</td>
                    <td class="text-center {{ $statusClass }}">{{ $status }}</td>
                    <td>{{ Str::limit($promotion->description, 50) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada produk difiturkan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <table style="width: 60%; margin-left: auto;">
            <tr>
                <td><strong>Total Produk Difiturkan:</strong></td>
                <td class="text-right"><strong>{{ $totalFeatured ?? 0 }} produk</strong></td>
            </tr>
            <tr>
                <td><strong>Produk Aktif Difiturkan:</strong></td>
                <td class="text-right status-active"><strong>{{ $activeFeatured ?? 0 }} produk</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh sistem pada {{ date('d/m/Y H:i:s') }}</p>
        <p>Kelapa Sawit UKM - Sistem Manajemen Inventori</p>
    </div>
</body>
</html>