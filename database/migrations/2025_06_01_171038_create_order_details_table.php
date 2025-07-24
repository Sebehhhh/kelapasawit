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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id(); // id (PK)
            $table->unsignedBigInteger('order_id');   // FK ke orders
            $table->unsignedBigInteger('product_id'); // FK ke products
            $table->integer('quantity');
            $table->decimal('price', 12, 2); // harga per item saat transaksi
            $table->unsignedBigInteger('promotion_id')->nullable();
            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->timestamps();
    
            // Foreign Key Constraints
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
