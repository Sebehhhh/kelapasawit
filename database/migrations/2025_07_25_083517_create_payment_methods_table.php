<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['e-wallet', 'rekening'])->comment('Jenis metode pembayaran');
            $table->string('name')->comment('Nama metode pembayaran (misal: Dana, BCA, Mandiri)');
            $table->string('account_number')->comment('Nomor rekening atau nomor e-wallet');
            $table->string('account_name')->comment('Nama pemilik rekening atau e-wallet');
            $table->text('instructions')->nullable()->comment('Instruksi pembayaran');
            $table->boolean('is_active')->default(true)->comment('Status aktif metode pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
