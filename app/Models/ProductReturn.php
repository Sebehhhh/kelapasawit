<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'reason',
        'return_date',
        'status',
        'note',
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class);
    }
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
