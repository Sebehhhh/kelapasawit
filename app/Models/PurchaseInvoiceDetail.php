<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceDetail extends Model
{
    protected $fillable = [
        'purchase_invoice_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function purchaseInvoice()
    {
        return $this->belongsTo(\App\Models\PurchaseInvoice::class);
    }
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    // Event untuk otomatis update stok saat pembelian dari pemasok
    protected static function booted()
    {
        static::created(function ($purchaseDetail) {
            // Tambah stok saat purchase detail dibuat (pembelian)
            $product = $purchaseDetail->product;
            if ($product) {
                $product->increment('stock', $purchaseDetail->quantity);
            }
        });

        static::updated(function ($purchaseDetail) {
            // Update stok saat purchase detail diubah
            $product = $purchaseDetail->product;
            if ($product && $purchaseDetail->isDirty('quantity')) {
                $oldQuantity = $purchaseDetail->getOriginal('quantity');
                $newQuantity = $purchaseDetail->quantity;
                $difference = $newQuantity - $oldQuantity;
                
                $product->increment('stock', $difference);
            }
        });

        static::deleted(function ($purchaseDetail) {
            // Kurangi stok saat purchase detail dihapus
            $product = $purchaseDetail->product;
            if ($product) {
                $product->decrement('stock', $purchaseDetail->quantity);
            }
        });
    }
}
