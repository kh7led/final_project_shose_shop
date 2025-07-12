<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * عرض قائمة المنتجات
     */
    public function index(Request $request)
    {
        // جلب الفئات مع عدد المنتجات
        $categories = Category::withCount('products')->get();

        // بناء استعلام المنتجات
        $query = Product::with('category')
                       ->where('quantity', '>', 0); // فقط المنتجات المتوفرة

        // فلترة حسب الفئة
        if ($request->has('category') && $request->category) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // البحث النصي
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // ترتيب المنتجات (الأحدث أولاً)
        $products = $query->latest()->paginate(12);

        // إضافة معاملات البحث لروابط الترقيم
        $products->appends($request->query());

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * عرض تفاصيل منتج واحد
     */
    public function show(Product $product)
    {
        // جلب المنتجات المشابهة من نفس الفئة
        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->where('quantity', '>', 0)
                                 ->limit(4)
                                 ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
