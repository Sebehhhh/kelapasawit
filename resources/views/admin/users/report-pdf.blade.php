<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pengguna</title>
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
        .role-admin { background: #e3f2fd; color: #1565c0; }
        .role-owner { background: #fff3e0; color: #ef6c00; }
        .role-customer { background: #f1f8e9; color: #2e7d32; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .summary-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DATA PENGGUNA</h2>
        <p>CV. Kelapa Sawit Unggul</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary-box">
        @php 
            $totalUsers = count($users);
            $adminCount = $users->where('role', 'admin')->count();
            $ownerCount = $users->where('role', 'owner')->count();
            $customerCount = $users->where('role', 'customer')->count();
        @endphp
        <div class="summary-item">
            <strong>Total Pengguna:</strong> {{ $totalUsers }} orang
        </div>
        <div class="summary-item">
            <strong>Admin:</strong> {{ $adminCount }} orang
        </div>
        <div class="summary-item">
            <strong>Owner:</strong> {{ $ownerCount }} orang
        </div>
        <div class="summary-item">
            <strong>Customer:</strong> {{ $customerCount }} orang
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%">No</th>
                <th width="25%">Nama Lengkap</th>
                <th width="30%">Email</th>
                <th width="15%">No HP</th>
                <th width="12%">Role</th>
                <th width="15%">Terdaftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="role-{{ $user->role }}">
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="text-center">{{ $user->phone ?? '-' }}</td>
                <td class="text-center">{{ ucfirst($user->role) }}</td>
                <td class="text-center">{{ $user->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <table style="width: 40%; margin-left: auto;">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center;">STATISTIK PENGGUNA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Admin</strong></td>
                    <td class="text-center">{{ $adminCount }} orang ({{ $totalUsers > 0 ? number_format(($adminCount/$totalUsers)*100, 1) : 0 }}%)</td>
                </tr>
                <tr>
                    <td><strong>Owner</strong></td>
                    <td class="text-center">{{ $ownerCount }} orang ({{ $totalUsers > 0 ? number_format(($ownerCount/$totalUsers)*100, 1) : 0 }}%)</td>
                </tr>
                <tr>
                    <td><strong>Customer</strong></td>
                    <td class="text-center">{{ $customerCount }} orang ({{ $totalUsers > 0 ? number_format(($customerCount/$totalUsers)*100, 1) : 0 }}%)</td>
                </tr>
                <tr class="total-row">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-center"><strong>{{ $totalUsers }} orang (100.0%)</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }} WIB</p>
        <p>Total {{ $totalUsers }} pengguna terdaftar dalam sistem</p>
    </div>
</body>
</html> 