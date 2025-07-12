<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    // العلاقة مع المنتجات
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // إعدادات الروابط
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // عدد المنتجات المتوفرة
    public function getAvailableProductsCountAttribute()
    {
        return $this->products()->where('quantity', '>', 0)->count();
    }

    // إجمالي مبيعات الفئة
    public function getTotalSalesAttribute()
    {
        return $this->products()
                   ->join('order_items', 'products.id', '=', 'order_items.product_id')
                   ->sum('order_items.quantity');
    }

    // البحث
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%");
    }

    // الفئات التي تحتوي على منتجات
    public function scopeWithProducts($query)
    {
        return $query->has('products');
    }
}
