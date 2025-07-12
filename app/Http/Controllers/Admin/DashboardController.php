<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            // الإحصائيات الأساسية
            $basicStats = $this->getBasicStats();

            // إحصائيات الطلبات
            $orderStats = $this->getOrderStats();

            // الطلبات الحديثة
            $recentOrders = $this->getRecentOrders();

            // المنتجات قليلة المخزون
            $lowStockProducts = $this->getLowStockProducts();

            // أفضل المنتجات مبيعاً
            $topSellingProducts = $this->getTopSellingProducts();

            // إحصائيات المبيعات الشهرية
            $monthlySales = $this->getMonthlySales();

            // نشاط المستخدمين الجدد
            $newUsersActivity = $this->getNewUsersActivity();

            // إحصائيات الفئات
            $categoryStats = $this->getCategoryStats();

            // إحصائيات سلة التسوق
            $cartStats = $this->getCartStats();

            return view('admin.dashboard', compact(
                'basicStats',
                'orderStats',
                'recentOrders',
                'lowStockProducts',
                'topSellingProducts',
                'monthlySales',
                'newUsersActivity',
                'categoryStats',
                'cartStats'
            ));

        } catch (\Exception $e) {
            Log::error('خطأ في تحميل لوحة التحكم: ' . $e->getMessage());

            // في حالة الخطأ، إرجاع بيانات أساسية
            $basicStats = [
                'total_products' => Product::count(),
                'total_orders' => Order::count(),
                'total_customers' => User::where('role', 'customer')->count(),
                'total_categories' => Category::count(),
            ];

            return view('admin.dashboard', compact('basicStats'))
                   ->with('error', 'حدث خطأ في تحميل بعض الإحصائيات');
        }
    }

    /**
     * الحصول على الإحصائيات الأساسية
     */
    private function getBasicStats()
    {
        return [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_categories' => Category::count(),
            'total_revenue' => Order::whereIn('status', ['processing', 'shipped', 'delivered'])->sum('total_price'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock_products' => Product::where('quantity', '<', 10)->count(),
            'out_of_stock' => Product::where('quantity', 0)->count(),
            'active_carts' => Cart::distinct('user_id')->count(),
        ];
    }

    /**
     * الحصول على إحصائيات الطلبات
     */
    private function getOrderStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),

            // إحصائيات زمنية
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'week_orders' => Order::where('created_at', '>=', $thisWeek)->count(),
            'month_orders' => Order::where('created_at', '>=', $thisMonth)->count(),

            // إيرادات زمنية
            'today_revenue' => Order::whereDate('created_at', $today)
                                   ->whereIn('status', ['processing', 'shipped', 'delivered'])
                                   ->sum('total_price'),
            'week_revenue' => Order::where('created_at', '>=', $thisWeek)
                                  ->whereIn('status', ['processing', 'shipped', 'delivered'])
                                  ->sum('total_price'),
            'month_revenue' => Order::where('created_at', '>=', $thisMonth)
                                   ->whereIn('status', ['processing', 'shipped', 'delivered'])
                                   ->sum('total_price'),

            // متوسط قيمة الطلب
            'average_order_value' => Order::whereIn('status', ['processing', 'shipped', 'delivered'])
                                         ->avg('total_price') ?? 0,
        ];
    }

    /**
     * الحصول على الطلبات الحديثة
     */
    private function getRecentOrders()
    {
        return Order::with(['user', 'orderItems.product'])
                   ->latest()
                   ->take(5)
                   ->get();
    }

    /**
     * الحصول على المنتجات قليلة المخزون
     */
    private function getLowStockProducts()
    {
        return Product::with('category')
                     ->where('quantity', '<', 10)
                     ->orderBy('quantity', 'asc')
                     ->take(5)
                     ->get();
    }

    /**
     * الحصول على أفضل المنتجات مبيعاً
     */
    private function getTopSellingProducts()
    {
        return Product::with(['category'])
                     ->withCount(['orderItems as total_sold' => function ($query) {
                         $query->select(DB::raw('SUM(quantity)'));
                     }])
                     ->having('total_sold', '>', 0)
                     ->orderBy('total_sold', 'desc')
                     ->take(5)
                     ->get();
    }

    /**
     * الحصول على إحصائيات المبيعات الشهرية
     */
    private function getMonthlySales()
    {
        $months = [];
        $revenues = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');

            $revenue = Order::whereYear('created_at', $month->year)
                           ->whereMonth('created_at', $month->month)
                           ->whereIn('status', ['processing', 'shipped', 'delivered'])
                           ->sum('total_price');

            $months[] = $monthName;
            $revenues[] = $revenue;
        }

        return [
            'months' => $months,
            'revenues' => $revenues,
            'total_6_months' => array_sum($revenues),
        ];
    }

    /**
     * الحصول على نشاط المستخدمين الجدد
     */
    private function getNewUsersActivity()
    {
        $last7Days = [];
        $userCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $date->format('D');

            $count = User::whereDate('created_at', $date)->count();

            $last7Days[] = $dayName;
            $userCounts[] = $count;
        }

        return [
            'days' => $last7Days,
            'counts' => $userCounts,
            'total_week' => array_sum($userCounts),
            'today_new_users' => User::whereDate('created_at', Carbon::today())->count(),
        ];
    }

    /**
     * الحصول على إحصائيات الفئات
     */
    private function getCategoryStats()
    {
        return Category::withCount(['products'])
                      ->with(['products' => function ($query) {
                          $query->withCount(['orderItems as total_sold' => function ($q) {
                              $q->select(DB::raw('SUM(quantity)'));
                          }]);
                      }])
                      ->get()
                      ->map(function ($category) {
                          $category->total_sold = $category->products->sum('total_sold');
                          return $category;
                      })
                      ->sortByDesc('total_sold');
    }

    /**
     * الحصول على إحصائيات سلة التسوق
     */
    private function getCartStats()
    {
        return [
            'total_carts' => Cart::count(),
            'active_users_with_carts' => Cart::distinct('user_id')->count(),
            'average_cart_items' => Cart::avg(DB::raw('quantity')) ?? 0,
            'abandoned_carts' => Cart::whereHas('user', function ($query) {
                                     $query->where('updated_at', '<', Carbon::now()->subDays(3));
                                 })->distinct('user_id')->count(),
            'cart_conversion_rate' => $this->calculateCartConversionRate(),
        ];
    }

    /**
     * حساب معدل تحويل سلة التسوق
     */
    private function calculateCartConversionRate()
    {
        $totalCartsWithItems = Cart::distinct('user_id')->count();
        $ordersThisMonth = Order::where('created_at', '>=', Carbon::now()->startOfMonth())->distinct('user_id')->count();

        if ($totalCartsWithItems > 0) {
            return round(($ordersThisMonth / $totalCartsWithItems) * 100, 2);
        }

        return 0;
    }

    /**
     * الحصول على إحصائيات المنتجات المطلوبة
     */
    public function getProductInsights()
    {
        return [
            'most_viewed' => $this->getMostViewedProducts(),
            'recently_added' => Product::latest()->take(5)->get(),
            'price_ranges' => $this->getProductPriceRanges(),
        ];
    }

    /**
     * الحصول على المنتجات الأكثر مشاهدة (يحتاج إضافة tracking)
     */
    private function getMostViewedProducts()
    {
        // يمكن إضافة tracking للمشاهدات لاحقاً
        return Product::with('category')->take(5)->get();
    }

    /**
     * الحصول على نطاقات الأسعار
     */
    private function getProductPriceRanges()
    {
        return [
            'under_100' => Product::where('price', '<', 100)->count(),
            '100_to_300' => Product::whereBetween('price', [100, 300])->count(),
            '300_to_500' => Product::whereBetween('price', [300, 500])->count(),
            'over_500' => Product::where('price', '>', 500)->count(),
        ];
    }

    /**
     * تصدير إحصائيات للتقارير
     */
    public function exportStats(Request $request)
    {
        $type = $request->get('type', 'orders');
        $format = $request->get('format', 'json');

        $data = [];

        switch ($type) {
            case 'orders':
                $data = $this->getOrderStats();
                break;
            case 'products':
                $data = $this->getProductInsights();
                break;
            case 'users':
                $data = $this->getNewUsersActivity();
                break;
            default:
                $data = $this->getBasicStats();
        }

        if ($format === 'json') {
            return response()->json($data);
        }

        // يمكن إضافة تصدير Excel/PDF لاحقاً
        return response()->json($data);
    }

    /**
     * الحصول على بيانات الرسوم البيانية
     */
    public function getChartData(Request $request)
    {
        $chart = $request->get('chart');

        switch ($chart) {
            case 'monthly_sales':
                return response()->json($this->getMonthlySales());
            case 'new_users':
                return response()->json($this->getNewUsersActivity());
            case 'order_status':
                return response()->json($this->getOrderStats());
            default:
                return response()->json(['error' => 'نوع الرسم البياني غير معروف']);
        }
    }
}
