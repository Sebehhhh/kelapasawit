<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'order_date', 'status', 'total_amount'];

    public function user()     { return $this->belongsTo(User::class); }
    public function details()  { return $this->hasMany(OrderDetail::class); }
    public function payment()  { return $this->hasOne(Payment::class); }
    public function shipping() { return $this->hasOne(Shipping::class); }
    public function invoice()  { return $this->hasOne(Invoice::class); }

    protected $casts = [
        'order_date' => 'datetime',
    ];
}