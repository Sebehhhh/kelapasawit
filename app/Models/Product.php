<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        // 'unit',
        'image',
        // 'status', // aktif/nonaktif
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke order detail (kalau sudah ada)
    public function orderItems()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function supplierPurchases()
    {
        return $this->hasMany(\App\Models\SupplierPurchase::class);
    }

    // Relasi ke promosi
    public function promotions()
    {
        return $this->hasMany(\App\Models\Promotion::class);
    }

    // Helper method untuk mendapatkan promosi aktif
    public function getActivePromotion()
    {
        return $this->promotions()
            ->active()
            ->first();
    }

    // Helper method untuk mendapatkan harga setelah diskon (per unit)
    public function getFinalPrice($quantity = 1)
    {
        $promotion = $this->getActivePromotion();
        if ($promotion) {
            return $promotion->getFinalPrice($this->price, $quantity);
        }
        return (float) $this->price; // Return per unit price
    }

    // Helper method untuk mendapatkan jumlah diskon total
    public function getDiscountAmount($quantity = 1)
    {
        $promotion = $this->getActivePromotion();
        if ($promotion) {
            return $promotion->calculateDiscount($this->price, $quantity);
        }
        return 0;
    }

    // Helper method untuk mengecek apakah produk memiliki diskon aktif
    public function hasActiveDiscount()
    {
        $promotion = $this->getActivePromotion();
        return $promotion && $promotion->discount_value > 0 && $promotion->isActive();
    }
}
