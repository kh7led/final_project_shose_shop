<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // إجمالي سعر العنصر
    public function getTotalPriceAttribute()
    {
        return $this->product->price * $this->quantity;
    }

    // تحقق من توفر المنتج
    public function isAvailable()
    {
        return $this->product && $this->product->quantity >= $this->quantity;
    }

    // السلة المهجورة
    public function scopeAbandoned($query, $days = 3)
    {
        return $query->where('updated_at', '<', now()->subDays($days));
    }
}
