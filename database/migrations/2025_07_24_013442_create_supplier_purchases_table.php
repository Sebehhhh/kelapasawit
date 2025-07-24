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
        Schema::create('supplier_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('unit_price', 15, 2);
            $table->integer('quantity');
            $table->decimal('total_price', 15, 2);
            $table->date('purchase_date');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_purchases');
    }
};
