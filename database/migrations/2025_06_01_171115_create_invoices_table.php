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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // id (PK)
            $table->unsignedBigInteger('order_id'); // FK ke orders
            $table->dateTime('invoice_date');
            $table->string('file_path', 255)->nullable(); // lokasi file pdf/print
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
        Schema::dropIfExists('invoices');
    }
};
