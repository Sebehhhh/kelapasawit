<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Order</title>
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
        <h1>LAPORAN ORDER</h1>
        <p>Kelapa Sawit Management System</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
        @if(isset($selectedStatus) || isset($selectedUser) || isset($tanggalMulai) || isset($tanggalSelesai))
            <p>
                Filter:
                @if(isset($selectedStatus) && $selectedStatus)
                    Status: {{ $selectedStatus }}
                @endif
                @if(isset($selectedUser) && $selectedUser)
                    | User: {{ optional($users->where('id', $selectedUser)->first())->name ?? '-' }}
                @endif
                @if(isset($tanggalMulai) && $tanggalMulai)
                    | Dari: {{ $tanggalMulai }}
                @endif
                @if(isset($tanggalSelesai) && $tanggalSelesai)
                    | Sampai: {{ $tanggalSelesai }}
                @endif
            </p>
        @endif
    </div>
    <div class="info-section">
        <h3>Daftar Order</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $index => $order)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : '-' }}</td>
                        <td>{{ $order->user->name ?? '-' }}</td>
                        <td>{{ $order->status }}</td>
                        <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Tidak ada data order</td>
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