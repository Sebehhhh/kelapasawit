<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'supplier_name',
        'purchase_date',
        'total',
        'note',
    ];

    public function details()
    {
        return $this->hasMany(\App\Models\PurchaseInvoiceDetail::class);
    }
}
