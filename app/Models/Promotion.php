<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'product_id', 'image',
        'discount_type', 'discount_value', 'min_purchase', 'max_discount', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if promotion is currently active
     */
    public function isActive()
    {
        $now = Carbon::now()->toDateString();
        $startDate = $this->start_date ? $this->start_date->toDateString() : null;
        $endDate = $this->end_date ? $this->end_date->toDateString() : null;
        
        return $this->is_active 
            && $startDate && $startDate <= $now 
            && $endDate && $endDate >= $now;
    }

    /**
     * Calculate discount amount for given price
     */
    public function calculateDiscount($originalPrice, $quantity = 1)
    {
        if (!$this->isActive()) {
            return 0;
        }

        // Ensure we work with proper decimal values
        $originalPrice = (float) $originalPrice;
        $quantity = (int) $quantity;
        $totalPrice = $originalPrice * $quantity;

        // Check minimum purchase requirement
        if ($this->min_purchase && $totalPrice < $this->min_purchase) {
            return 0;
        }

        $discount = 0;

        if ($this->discount_type === 'percentage') {
            // For percentage, calculate with proper precision
            $discountRate = (float) $this->discount_value;
            $discount = round(($totalPrice * $discountRate) / 100, 2);
        } else {
            // For fixed amount
            $discountValue = (float) $this->discount_value;
            $discount = $discountValue * $quantity;
        }

        // Apply maximum discount limit if set
        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = (float) $this->max_discount;
        }

        return round($discount, 2);
    }

    /**
     * Get final price after discount (per unit)
     */
    public function getFinalPrice($originalPrice, $quantity = 1)
    {
        $originalPrice = (float) $originalPrice;
        $quantity = (int) $quantity;
        $totalPrice = $originalPrice * $quantity;
        $discount = $this->calculateDiscount($originalPrice, $quantity);
        $finalTotal = $totalPrice - $discount;
        $finalPricePerUnit = $finalTotal / $quantity;
        
        // Round to 2 decimal places to avoid floating point precision issues
        return round($finalPricePerUnit, 2);
    }

    /**
     * Scope for active promotions
     */
    public function scopeActive($query)
    {
        $now = Carbon::now()->toDateString();
        return $query->where('is_active', true)
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    /**
     * Scope for promotions by product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
}
