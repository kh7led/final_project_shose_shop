<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // العلاقة مع الطلبات
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // العلاقة مع السلة
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // تحقق من كون المستخدم مدير
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // تحقق من كون المستخدم عميل
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    // إجمالي مشتريات المستخدم
    public function getTotalPurchasesAttribute()
    {
        return $this->orders()->sum('total_price');
    }

    // متوسط قيمة الطلب
    public function getAverageOrderValueAttribute()
    {
        return $this->orders()->avg('total_price') ?: 0;
    }

    // عدد المنتجات في السلة
    public function getCartItemsCountAttribute()
    {
        return $this->carts()->sum('quantity');
    }

    // إجمالي قيمة السلة
    public function getCartTotalAttribute()
    {
        return $this->carts()->with('product')->get()->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
    }

    // آخر طلب
    public function getLastOrderAttribute()
    {
        return $this->orders()->latest()->first();
    }

    // البحث في المستخدمين
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    // تصفية حسب الدور
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // المستخدمين الجدد
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}