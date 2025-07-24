<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'price', 'promotion_id', 'original_price', 'discount_amount'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function order()   { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function promotion() { return $this->belongsTo(Promotion::class); }

    public function testimonial()
    {
        return $this->hasOne(\App\Models\Testimonial::class);
    }

    // Event untuk otomatis update stok saat penjualan
    protected static function booted()
    {
        static::created(function ($orderDetail) {
            // Kurangi stok saat order detail dibuat (penjualan)
            $product = $orderDetail->product;
            if ($product) {
                $product->decrement('stock', $orderDetail->quantity);
            }
        });

        static::updated(function ($orderDetail) {
            // Update stok saat order detail diubah
            $product = $orderDetail->product;
            if ($product && $orderDetail->isDirty('quantity')) {
                $oldQuantity = $orderDetail->getOriginal('quantity');
                $newQuantity = $orderDetail->quantity;
                $difference = $newQuantity - $oldQuantity;
                
                $product->decrement('stock', $difference);
            }
        });

        static::deleted(function ($orderDetail) {
            // Kembalikan stok saat order detail dihapus
            $product = $orderDetail->product;
            if ($product) {
                $product->increment('stock', $orderDetail->quantity);
            }
        });
    }
}