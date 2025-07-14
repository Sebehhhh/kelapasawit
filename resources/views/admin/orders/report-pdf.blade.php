<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #7f8c8d;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h3 {
            color: #2c3e50;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stats-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        .stats-number {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .stats-label {
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #2c3e50;
        }
        .status-pending { color: #f39c12; font-weight: bold; }
        .status-paid { color: #27ae60; font-weight: bold; }
        .status-shipped { color: #3498db; font-weight: bold; }
        .status-cancelled { color: #e74c3c; font-weight: bold; }
        .total-row {
            background-color: #ecf0f1;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ORDER</h1>
        <p>Kelapa Sawit Management System</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
        @if(isset($startDate) && $startDate || isset($endDate) && $endDate || isset($status) && $status)
            <p>
                Filter: 
                @if(isset($startDate) && $startDate) Dari: {{ date('d/m/Y', strtotime($startDate)) }} @endif
                @if(isset($endDate) && $endDate) Sampai: {{ date('d/m/Y', strtotime($endDate)) }} @endif
                @if(isset($status) && $status) Status: {{ ucfirst($status) }} @endif
            </p>
        @endif
    </div>

    <div class="info-section">
        <h3>Ringkasan Statistik</h3>
        <div class="stats-grid">
            <div class="stats-item">
                <div class="stats-number">{{ $totalOrders }}</div>
                <div class="stats-label">Total Order</div>
            </div>
            <div class="stats-item">
                <div class="stats-number">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="stats-label">Total Pendapatan</div>
            </div>
            <div class="stats-item">
                <div class="stats-number">{{ $paidOrders }}</div>
                <div class="stats-label">Order Dibayar</div>
            </div>
            <div class="stats-item">
                <div class="stats-number">{{ $pendingOrders }}</div>
                <div class="stats-label">Order Pending</div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3>Detail Order</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Item</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $index => $order)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : '-' }}</td>
                        <td>{{ $order->user->name ?? '-' }}</td>
                        <td>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="status-{{ $order->status }}">{{ ucfirst($order->status) }}</td>
                        <td>
                            @foreach($order->details as $detail)
                                {{ $detail->product->name ?? '-' }} ({{ $detail->quantity }}x)<br>
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Tidak ada data order</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="info-section">
        <h3>Statistik Status Order</h3>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Jumlah</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pending</td>
                    <td>{{ $pendingOrders }}</td>
                    <td>{{ $totalOrders > 0 ? round(($pendingOrders / $totalOrders) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Paid</td>
                    <td>{{ $paidOrders }}</td>
                    <td>{{ $totalOrders > 0 ? round(($paidOrders / $totalOrders) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Shipped</td>
                    <td>{{ $shippedOrders }}</td>
                    <td>{{ $totalOrders > 0 ? round(($shippedOrders / $totalOrders) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Cancelled</td>
                    <td>{{ $cancelledOrders }}</td>
                    <td>{{ $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100, 1) : 0 }}%</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total</strong></td>
                    <td><strong>{{ $totalOrders }}</strong></td>
                    <td><strong>100%</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem Kelapa Sawit Management</p>
        <p>© {{ date('Y') }} Kelapa Sawit Management System. All rights reserved.</p>
    </div>
</body>
</html> 