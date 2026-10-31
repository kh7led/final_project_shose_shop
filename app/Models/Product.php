<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'image',
        'video',
        'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    // العلاقة مع الفئة
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // العلاقة مع عناصر الطلبات
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // العلاقة مع السلة
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // إعدادات الروابط
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // اكسسر للصورة
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    // اكسسر للفيديو
    public function getVideoUrlAttribute()
    {
        return $this->video ? asset('storage/' . $this->video) : null;
    }

    // تحقق من توفر المنتج
    public function isAvailable()
    {
        return $this->quantity > 0;
    }

    // حالة المخزون
    public function getStockStatusAttribute()
    {
        if ($this->quantity == 0) {
            return 'out_of_stock';
        } elseif ($this->quantity < 10) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    // نص حالة المخزون
    public function getStockStatusTextAttribute()
    {
        switch ($this->stock_status) {
            case 'out_of_stock':
                return 'نفذت الكمية';
            case 'low_stock':
                return 'كمية قليلة';
            default:
                return 'متوفر';
        }
    }

    // إجمالي المبيعات
    public function getTotalSoldAttribute()
    {
        return $this->orderItems()->sum('quantity');
    }

    // البحث
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    // تصفية حسب الفئة
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // المنتجات المتوفرة
    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    // المنتجات قليلة المخزون
    public function scopeLowStock($query)
    {
        return $query->where('quantity', '<', 10)->where('quantity', '>', 0);
    }

    // المنتجات الأكثر مبيعاً
    public function scopeTopSelling($query, $limit = 5)
    {
        return $query->withCount(['orderItems as total_sold' => function ($q) {
            $q->selectRaw('SUM(quantity)');
        }])
        ->having('total_sold', '>', 0)
        ->orderBy('total_sold', 'desc')
        ->limit($limit);
    }
}
