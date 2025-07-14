<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Promosi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #2c3e50; font-size: 24px; }
        .header p { margin: 5px 0; color: #7f8c8d; }
        .info-section { margin-bottom: 20px; }
        .info-section h3 { color: #2c3e50; border-bottom: 1px solid #bdc3c7; padding-bottom: 5px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; color: #2c3e50; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #7f8c8d; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PROMOSI</h1>
        <p>Kelapa Sawit Management System</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
        @if(isset($searchJudul))
            <p>Filter: Judul: {{ $searchJudul }}</p>
        @endif
    </div>
    <div class="info-section">
        <h3>Daftar Promosi</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotions as $index => $promotion)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $promotion->judul ?? $promotion->title }}</td>
                        <td>{{ $promotion->deskripsi ?? $promotion->description }}</td>
                        <td>{{ $promotion->tanggal_mulai ?? $promotion->start_date }}</td>
                        <td>{{ $promotion->tanggal_selesai ?? $promotion->end_date }}</td>
                        <td>{{ $promotion->status ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Tidak ada data promosi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="footer">
        <p><strong>Kelapa Sawit Management System</strong></p>
        <p>© {{ date('Y') }} Kelapa Sawit Management System. All rights reserved.</p>
    </div>
</body>
</html> 