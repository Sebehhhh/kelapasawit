<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // E-Wallet
        PaymentMethod::create([
            'type' => 'e-wallet',
            'name' => 'Dana',
            'account_number' => '081234567890',
            'account_name' => 'UKM Kelapa Sawit',
            'instructions' => 'Transfer melalui aplikasi Dana ke nomor yang tertera. Setelah transfer, kirim screenshot bukti pembayaran.'
        ]);

        PaymentMethod::create([
            'type' => 'e-wallet',
            'name' => 'GoPay',
            'account_number' => '081234567890',
            'account_name' => 'UKM Kelapa Sawit',
            'instructions' => 'Transfer melalui aplikasi Gojek ke nomor yang tertera. Setelah transfer, kirim screenshot bukti pembayaran.'
        ]);

        PaymentMethod::create([
            'type' => 'e-wallet',
            'name' => 'OVO',
            'account_number' => '081234567890',
            'account_name' => 'UKM Kelapa Sawit',
            'instructions' => 'Transfer melalui aplikasi OVO ke nomor yang tertera. Setelah transfer, kirim screenshot bukti pembayaran.'
        ]);

        // Transfer Bank
        PaymentMethod::create([
            'type' => 'rekening',
            'name' => 'Bank BCA',
            'account_number' => '1234567890',
            'account_name' => 'UKM Kelapa Sawit',
            'instructions' => 'Transfer ke rekening BCA yang tertera. Setelah transfer, kirim foto bukti transfer.'
        ]);

        PaymentMethod::create([
            'type' => 'rekening',
            'name' => 'Bank Mandiri',
            'account_number' => '1234567890123',
            'account_name' => 'UKM Kelapa Sawit',
            'instructions' => 'Transfer ke rekening Mandiri yang tertera. Setelah transfer, kirim foto bukti transfer.'
        ]);

        PaymentMethod::create([
            'type' => 'rekening',
            'name' => 'Bank BRI',
            'account_number' => '123456789012345',
            'account_name' => 'UKM Kelapa Sawit',
            'instructions' => 'Transfer ke rekening BRI yang tertera. Setelah transfer, kirim foto bukti transfer.'
        ]);
    }
}
