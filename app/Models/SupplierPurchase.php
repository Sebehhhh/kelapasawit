<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPurchase extends Model
{
    protected $fillable = [
        'supplier_name',
        'category_id',
        'product_id',
        'unit_price',
        'quantity',
        'total_price',
        'purchase_date',
        'note',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
