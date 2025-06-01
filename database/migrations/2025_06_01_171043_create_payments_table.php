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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // id (PK)
            $table->unsignedBigInteger('order_id'); // FK ke orders
            $table->dateTime('payment_date');
            $table->decimal('amount_paid', 12, 2);
            $table->string('payment_method', 50); // contoh: Bank Transfer, E-Wallet
            $table->string('proof_image', 255)->nullable(); // bukti pembayaran
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->timestamps();
    
            // Foreign Key Constraint
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
