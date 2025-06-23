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
}
