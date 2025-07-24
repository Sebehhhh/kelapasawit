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

    protected $casts = [
        'order_date' => 'datetime',
    ];
    
    /**
     * Restore stock for all items in this order
     */
    public function restoreStock()
    {
        foreach ($this->details as $detail) {
            if ($detail->product) {
                $detail->product->increment('stock', $detail->quantity);
            }
        }
    }
    
    /**
     * Reserve stock for all items in this order
     */
    public function reserveStock()
    {
        foreach ($this->details as $detail) {
            if ($detail->product) {
                // Check if stock is sufficient
                if ($detail->product->stock < $detail->quantity) {
                    throw new \Exception("Stok {$detail->product->name} tidak cukup! Sisa stok: {$detail->product->stock}");
                }
                $detail->product->decrement('stock', $detail->quantity);
            }
        }
    }
}