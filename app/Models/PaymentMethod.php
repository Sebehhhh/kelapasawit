<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'type',
        'name',
        'account_number',
        'account_name',
        'instructions'
    ];

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
