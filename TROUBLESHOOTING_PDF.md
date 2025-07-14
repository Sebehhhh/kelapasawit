# Troubleshooting Fitur PDF - Kelapa Sawit Management System

## ✅ Status: FITUR PDF SUDAH BERFUNGSI DENGAN BAIK

Berdasarkan test yang telah dilakukan, semua komponen fitur PDF sudah berfungsi dengan baik:

- ✅ Package DomPDF terinstall
- ✅ Data order tersedia (22 orders)
- ✅ Template PDF berhasil di-render
- ✅ PDF berhasil di-generate (7.2KB)
- ✅ Route dan middleware berfungsi

## 🔧 Jika Masih Mengalami Masalah

### 1. **Halaman Kosong/Tidak Ada Data**

**Kemungkinan Penyebab:**
- Belum ada data order
- User tidak login sebagai admin
- Session expired

**Solusi:**
```bash
# Jalankan seeder untuk membuat data sample
php artisan db:seed --class=OrderSeeder

# Cek data yang tersedia
php artisan tinker --execute="echo 'Orders: ' . App\Models\Order::count() . PHP_EOL;"
```

### 2. **Error "Class 'Barryvdh\DomPDF\Facade\Pdf' not found"**

**Solusi:**
```bash
# Reinstall package DomPDF
composer remove barryvdh/laravel-dompdf
composer require barryvdh/laravel-dompdf

# Clear cache
php artisan config:clear
php artisan cache:clear
```

### 3. **Error "View not found"**

**Solusi:**
```bash
# Clear view cache
php artisan view:clear

# Pastikan file template ada
ls resources/views/admin/orders/report-pdf.blade.php
ls resources/views/admin/orders/invoice-pdf.blade.php
```

### 4. **PDF Kosong/Tidak Ada Konten**

**Kemungkinan Penyebab:**
- Data order kosong
- Relasi model tidak ter-load dengan benar

**Solusi:**
```bash
# Cek data order dengan relasi
php artisan tinker --execute="
\$order = App\Models\Order::with(['user', 'details.product'])->first();
if (\$order) {
    echo 'Order ID: ' . \$order->id . PHP_EOL;
    echo 'Customer: ' . (\$order->user->name ?? 'N/A') . PHP_EOL;
    echo 'Details: ' . \$order->details->count() . PHP_EOL;
} else {
    echo 'Tidak ada order' . PHP_EOL;
}
"
```

### 5. **Error Permission/Akses**

**Solusi:**
```bash
# Pastikan user login sebagai admin
php artisan tinker --execute="
\$admin = App\Models\User::where('role', 'admin')->first();
echo 'Admin: ' . (\$admin ? \$admin->name : 'Tidak ada admin') . PHP_EOL;
"

# Cek middleware
php artisan route:list --name=admin.orders
```

### 6. **PDF Tidak Download**

**Kemungkinan Penyebab:**
- Browser memblokir popup
- JavaScript tidak berfungsi

**Solusi:**
- Izinkan popup untuk domain localhost
- Pastikan JavaScript enabled
- Coba browser berbeda

## 🧪 Test Manual

Jika masih ada masalah, jalankan test manual ini:

```bash
# Test 1: Cek data
php artisan tinker --execute="echo 'Orders: ' . App\Models\Order::count() . PHP_EOL;"

# Test 2: Cek template
php artisan tinker --execute="
use Illuminate\Support\Facades\View;
\$orders = App\Models\Order::with(['user', 'details.product'])->get();
\$data = ['orders' => \$orders, 'totalOrders' => \$orders->count(), 'totalRevenue' => \$orders->sum('total_amount'), 'pendingOrders' => 0, 'paidOrders' => 0, 'shippedOrders' => 0, 'cancelledOrders' => 0];
\$html = View::make('admin.orders.report-pdf', \$data)->render();
echo 'Template OK: ' . strlen(\$html) . ' chars' . PHP_EOL;
"

# Test 3: Cek PDF generation
php artisan tinker --execute="
use Barryvdh\DomPDF\Facade\Pdf;
\$orders = App\Models\Order::with(['user', 'details.product'])->get();
\$data = ['orders' => \$orders, 'totalOrders' => \$orders->count(), 'totalRevenue' => \$orders->sum('total_amount'), 'pendingOrders' => 0, 'paidOrders' => 0, 'shippedOrders' => 0, 'cancelledOrders' => 0];
\$pdf = Pdf::loadView('admin.orders.report-pdf', \$data);
\$content = \$pdf->output();
echo 'PDF OK: ' . strlen(\$content) . ' bytes' . PHP_EOL;
"
```

## 📋 Checklist Sebelum Menggunakan

- [ ] Package DomPDF terinstall
- [ ] Data order tersedia
- [ ] User login sebagai admin
- [ ] Route terdaftar dengan benar
- [ ] Template PDF ada dan valid
- [ ] Browser mengizinkan popup

## 🆘 Jika Masih Bermasalah

1. **Cek log error:**
```bash
tail -f storage/logs/laravel.log
```

2. **Cek versi PHP dan Laravel:**
```bash
php --version
php artisan --version
```

3. **Restart server:**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

4. **Clear semua cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 📞 Informasi Kontak

Jika masih mengalami masalah, pastikan untuk memberikan informasi:
- Error message lengkap
- Versi PHP dan Laravel
- Output dari test manual di atas
- Screenshot halaman yang bermasalah 