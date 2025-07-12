<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // احدث المنتجات
        $latestProducts = Product::with('category')
                                ->where('quantity', '>', 0)
                                ->latest()
                                ->take(8)
                                ->get();

        // المنتجات الأكثر مبيعاً
        $topSellingProducts = Product::with(['category'])
                                   ->withCount(['orderItems as total_sold' => function ($query) {
                                       $query->selectRaw('SUM(quantity)');
                                   }])
                                   ->having('total_sold', '>', 0)
                                   ->orderBy('total_sold', 'desc')
                                   ->where('quantity', '>', 0)
                                   ->take(6)
                                   ->get();

        // المنتجات المميزة (أغلى المنتجات)
        $featuredProducts = Product::with('category')
                                 ->where('quantity', '>', 0)
                                 ->orderBy('price', 'desc')
                                 ->take(4)
                                 ->get();

        // الفئات
        $categories = Category::withCount(['products' => function($query) {
                                  $query->where('quantity', '>', 0);
                              }])
                              ->having('products_count', '>', 0)
                              ->take(6)
                              ->get();

        // إحصائيات للصفحة الرئيسية
        $stats = [
            'total_products' => Product::where('quantity', '>', 0)->count(),
            'total_categories' => Category::has('products')->count(),
            'happy_customers' => 1250, // رقم تقديري
            'orders_completed' => 3400 // رقم تقديري
        ];

        return view('home', compact(
            'latestProducts',
            'topSellingProducts',
            'featuredProducts',
            'categories',
            'stats'
        ));
    }
}
