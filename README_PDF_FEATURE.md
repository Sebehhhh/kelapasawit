# Fitur Cetak PDF - Kelapa Sawit Management System

## Fitur yang Tersedia

### 1. Cetak Laporan Order (PDF)
- **Lokasi**: Menu Admin > Order Masuk > Tombol "Cetak Laporan"
- **Fungsi**: Mencetak laporan order dalam format PDF dengan filter
- **Filter yang tersedia**:
  - Tanggal mulai dan akhir
  - Status order (pending, paid, shipped, cancelled)
- **Konten laporan**:
  - Ringkasan statistik (total order, pendapatan, dll)
  - Detail order dengan informasi customer
  - Statistik status order dengan persentase
  - Informasi filter yang digunakan

### 2. Cetak Invoice Individual (PDF)
- **Lokasi**: Modal Detail Order > Tombol "Cetak Invoice"
- **Fungsi**: Mencetak invoice untuk order tertentu
- **Konten invoice**:
  - Header dengan informasi perusahaan
  - Informasi customer dan order
  - Detail produk dengan harga dan quantity
  - Informasi pengiriman (jika ada)
  - Informasi pembayaran (jika ada)
  - Total pembayaran

## Cara Penggunaan

### Cetak Laporan Order
1. Login sebagai admin
2. Akses menu "Order Masuk"
3. Klik tombol "Cetak Laporan" (hijau)
4. Pilih filter yang diinginkan:
   - Tanggal mulai dan akhir (default: 30 hari terakhir)
   - Status order (opsional)
5. Klik "Cetak PDF"
6. File PDF akan otomatis terdownload

### Cetak Invoice Individual
1. Login sebagai admin
2. Akses menu "Order Masuk"
3. Klik tombol "Detail" pada order yang diinginkan
4. Di modal detail, klik tombol "Cetak Invoice" (biru)
5. File PDF invoice akan otomatis terdownload

## Teknologi yang Digunakan

- **Package**: `barryvdh/laravel-dompdf`
- **Template**: Blade template dengan CSS styling
- **Format**: PDF yang dapat di-download

## File yang Dimodifikasi/Dibuat

### Controller
- `app/Http/Controllers/Admin/OrderController.php`
  - Method `printReport()` - untuk cetak laporan
  - Method `printInvoice($id)` - untuk cetak invoice individual

### Views
- `resources/views/admin/orders/report-pdf.blade.php` - Template laporan PDF
- `resources/views/admin/orders/invoice-pdf.blade.php` - Template invoice PDF
- `resources/views/admin/orders/index.blade.php` - Tambahan tombol dan modal

### Routes
- `routes/web.php` - Tambahan route untuk fitur PDF

## Instalasi

1. Install package DomPDF:
```bash
composer require barryvdh/laravel-dompdf
```

2. Pastikan data order tersedia (gunakan seeder jika perlu):
```bash
php artisan db:seed --class=OrderSeeder
```

## Catatan

- Fitur ini memerlukan data order yang valid untuk berfungsi dengan baik
- PDF akan otomatis terdownload saat tombol diklik
- Template PDF sudah dioptimasi untuk tampilan yang profesional
- Filter tanggal default adalah 30 hari terakhir
- Semua status order didukung (pending, paid, shipped, cancelled) 