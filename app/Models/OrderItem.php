<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public $timestamps = false;

    // العلاقة مع الطلب
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // العلاقة مع المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // إجمالي سعر العنصر
    public function getTotalPriceAttribute()
    {
        return $this->price * $this->quantity;
    }
}
