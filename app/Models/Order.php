<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2'
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع عناصر الطلب
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // إجمالي عدد المنتجات
    public function getTotalItemsAttribute()
    {
        return $this->orderItems()->sum('quantity');
    }

    // حالة الطلب بالعربي
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'في الانتظار',
            'processing' => 'قيد المعالجة',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // لون حالة الطلب
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    // تحقق من إمكانية إلغاء الطلب
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    // تحقق من كون الطلب مكتمل
    public function isCompleted()
    {
        return $this->status === 'delivered';
    }

    // البحث في الطلبات
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // طلبات اليوم
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // طلبات الأسبوع
    public function scopeThisWeek($query)
    {
        return $query->where('created_at', '>=', now()->startOfWeek());
    }

    // طلبات الشهر
    public function scopeThisMonth($query)
    {
        return $query->where('created_at', '>=', now()->startOfMonth());
    }
}
