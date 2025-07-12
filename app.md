# مشروع app - محسن للذكاء الاصطناعي

**تاريخ الإنشاء:** 2025-07-06 14:49:08
**نوع المشروع:** مشروع عام
**اللغات:** PHP
**عدد الملفات:** 49

## 📁 هيكل المشروع

```
app
├── Console
│   └── Kernel.php
├── Exceptions
│   └── Handler.php
├── Http
│   ├── Controllers
│   │   ├── Admin
│   │   │   ├── CategoryController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── OrderController.php
│   │   │   ├── ProductController.php
│   │   │   └── UserController.php
│   │   ├── Api
│   │   │   └── ProductController.php
│   │   ├── Auth
│   │   │   ├── AuthController.php
│   │   │   ├── AuthenticatedSessionController.php
│   │   │   ├── ConfirmablePasswordController.php
│   │   │   ├── EmailVerificationNotificationController.php
│   │   │   ├── EmailVerificationPromptController.php
│   │   │   ├── NewPasswordController.php
│   │   │   ├── PasswordController.php
│   │   │   ├── PasswordResetLinkController.php
│   │   │   ├── RegisteredUserController.php
│   │   │   └── VerifyEmailController.php
│   │   ├── CartController.php
│   │   ├── Controller.php
│   │   ├── HomeController.php
│   │   ├── OrderController.php
│   │   ├── ProductController.php
│   │   └── ProfileController.php
│   ├── Kernel.php
│   ├── Middleware
│   │   ├── Authenticate.php
│   │   ├── EncryptCookies.php
│   │   ├── IsAdmin.php
│   │   ├── PreventRequestsDuringMaintenance.php
│   │   ├── RedirectIfAuthenticated.php
│   │   ├── TrimStrings.php
│   │   ├── TrustHosts.php
│   │   ├── TrustProxies.php
│   │   └── VerifyCsrfToken.php
│   └── Requests
│       ├── Auth
│       │   └── LoginRequest.php
│       └── ProfileUpdateRequest.php
├── Models
│   ├── Cart.php
│   ├── Category.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Product.php
│   └── User.php
├── Providers
│   ├── AppServiceProvider.php
│   ├── AuthServiceProvider.php
│   ├── BroadcastServiceProvider.php
│   ├── EventServiceProvider.php
│   └── RouteServiceProvider.php
└── View
    └── Components
        ├── AppLayout.php
        └── GuestLayout.php
```

## 📋 ملخص المشروع

هذا المشروع يحتوي على:
- **PHP:** 0 ملف

## 📄 محتوى الملفات

### 1. Console\Kernel.php

**نوع:** php | **الحجم:** 681 بايت

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

```

### 2. Exceptions\Handler.php

**نوع:** php | **الحجم:** 824 بايت

```php
<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}

```

### 3. Http\Kernel.php

**نوع:** php | **الحجم:** 2,641 بايت

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'is_admin' => \App\Http\Middleware\IsAdmin::class,

    ];
}

```

### 4. Http\Controllers\CartController.php

**نوع:** php | **الحجم:** 12,556 بايت

```php
<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // ========================================
    // الوظائف الأساسية للسلة
    // ========================================

    public function index()
    {
        $cartItems = Cart::with(['product.category'])
                        ->where('user_id', auth()->id())
                        ->get();

        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    // ========================================
    // النظام القديم - للنماذج (POST /cart)
    // ========================================
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:10'
            ]);

            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;

            $result = $this->addProductToCart($product, $quantity);

            if ($result['success']) {
                return back()->with('success', $result['message']);
            } else {
                return back()->with('error', $result['message']);
            }

        } catch (\Exception $e) {
            Log::error('Cart store error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إضافة المنتج للسلة');
        }
    }

    // ========================================
    // النظام الجديد - للـ AJAX (POST /cart/add/{product}) 🎯
    // ========================================
    public function add(Request $request, Product $product)
    {
        try {
            $request->validate([
                'quantity' => 'integer|min:1|max:10'
            ]);

            $quantity = $request->input('quantity', 1);
            $result = $this->addProductToCart($product, $quantity);

            // للطلبات AJAX
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => $result['success'],
                    'message' => $result['message'],
                    'cart_count' => $this->getCartCount(),
                    'product_name' => $product->name,
                    'product_id' => $product->id
                ], $result['success'] ? 200 : 422);
            }

            // للطلبات العادية
            if ($result['success']) {
                return back()->with('success', $result['message']);
            } else {
                return back()->with('error', $result['message']);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->with('error', 'بيانات غير صحيحة');

        } catch (\Exception $e) {
            Log::error('Cart add error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إضافة المنتج للسلة'
                ], 500);
            }
            return back()->with('error', 'حدث خطأ أثناء إضافة المنتج للسلة');
        }
    }

    // ========================================
    // دالة مشتركة لإضافة المنتج للسلة
    // ========================================
    private function addProductToCart(Product $product, int $quantity): array
    {
        // التحقق من توفر المنتج
        if ($product->quantity <= 0) {
            return [
                'success' => false,
                'message' => 'هذا المنتج غير متوفر حالياً'
            ];
        }

        // التحقق من توفر الكمية المطلوبة
        if ($quantity > $product->quantity) {
            return [
                'success' => false,
                'message' => "الكمية المطلوبة ({$quantity}) أكبر من المتوفر ({$product->quantity})"
            ];
        }

        // البحث عن المنتج في السلة
        $existingItem = Cart::where('user_id', auth()->id())
                           ->where('product_id', $product->id)
                           ->first();

        if ($existingItem) {
            // تحديث الكمية
            $newQuantity = $existingItem->quantity + $quantity;

            if ($newQuantity > $product->quantity) {
                return [
                    'success' => false,
                    'message' => "الكمية الإجمالية ({$newQuantity}) تتجاوز المخزون المتاح ({$product->quantity})"
                ];
            }

            $existingItem->update(['quantity' => $newQuantity]);

            return [
                'success' => true,
                'message' => "تم تحديث كمية {$product->name} في السلة"
            ];

        } else {
            // إضافة منتج جديد
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);

            return [
                'success' => true,
                'message' => "تم إضافة {$product->name} للسلة بنجاح"
            ];
        }
    }

    // ========================================
    // تحديث المنتج في السلة
    // ========================================
    public function update(Request $request, Cart $cart)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:10'
            ]);

            // التحقق من الملكية
            if ($cart->user_id !== auth()->id()) {
                abort(403, 'غير مسموح لك بتعديل هذا العنصر');
            }

            // التحقق من توفر الكمية
            if ($request->quantity > $cart->product->quantity) {
                return back()->with('error', 'الكمية المطلوبة غير متوفرة');
            }

            $cart->update(['quantity' => $request->quantity]);

            return back()->with('success', 'تم تحديث الكمية بنجاح');

        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحديث الكمية');
        }
    }

    // ========================================
    // حذف المنتج من السلة (النظام القديم)
    // ========================================
    public function destroy(Cart $cart)
    {
        try {
            // التحقق من الملكية
            if ($cart->user_id !== auth()->id()) {
                abort(403, 'غير مسموح لك بحذف هذا العنصر');
            }

            $productName = $cart->product->name;
            $cart->delete();

            return back()->with('success', "تم حذف {$productName} من السلة");

        } catch (\Exception $e) {
            Log::error('Cart destroy error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف المنتج');
        }
    }

    // ========================================
    // حذف المنتج من السلة (النظام الجديد - AJAX)
    // ========================================
    public function remove(Request $request, Cart $cart)
    {
        try {
            // التحقق من الملكية
            if ($cart->user_id !== auth()->id()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'غير مسموح لك بحذف هذا العنصر'
                    ], 403);
                }
                abort(403);
            }

            $productName = $cart->product->name;
            $cart->delete();

            // للطلبات AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "تم حذف {$productName} من السلة",
                    'cart_count' => $this->getCartCount()
                ]);
            }

            // للطلبات العادية
            return back()->with('success', "تم حذف {$productName} من السلة");

        } catch (\Exception $e) {
            Log::error('Cart remove error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حذف المنتج'
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء حذف المنتج');
        }
    }

    // ========================================
    // API endpoints للـ JavaScript
    // ========================================

    // الحصول على عدد المنتجات في السلة
    public function count()
    {
        try {
            $count = $this->getCartCount();
            return response()->json(['count' => $count]);

        } catch (\Exception $e) {
            Log::error('Cart count error: ' . $e->getMessage());
            return response()->json(['count' => 0]);
        }
    }

    // الحصول على جميع عناصر السلة (JSON)
    public function items()
    {
        try {
            $cartItems = Cart::with(['product:id,name,price,image'])
                            ->where('user_id', auth()->id())
                            ->get()
                            ->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'product_id' => $item->product_id,
                                    'product_name' => $item->product->name,
                                    'product_price' => $item->product->price,
                                    'product_image' => $item->product->image,
                                    'quantity' => $item->quantity,
                                    'total' => $item->product->price * $item->quantity
                                ];
                            });

            $total = $cartItems->sum('total');

            return response()->json([
                'items' => $cartItems,
                'total' => $total,
                'count' => $cartItems->sum('quantity')
            ]);

        } catch (\Exception $e) {
            Log::error('Cart items error: ' . $e->getMessage());
            return response()->json([
                'items' => [],
                'total' => 0,
                'count' => 0
            ]);
        }
    }

    // ========================================
    // دوال مساعدة
    // ========================================

    private function getCartCount(): int
    {
        if (!auth()->check()) {
            return 0;
        }

        return Cart::where('user_id', auth()->id())->sum('quantity');
    }

    // تفريغ السلة (مفيد بعد إتمام الطلب)
    public function clear()
    {
        try {
            Cart::where('user_id', auth()->id())->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم تفريغ السلة بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Cart clear error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تفريغ السلة'
            ], 500);
        }
    }
}

```

### 5. Http\Controllers\Controller.php

**نوع:** php | **الحجم:** 361 بايت

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

```

### 6. Http\Controllers\HomeController.php

**نوع:** php | **الحجم:** 2,362 بايت

```php
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

```

### 7. Http\Controllers\OrderController.php

**نوع:** php | **الحجم:** 2,519 بايت

```php
<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems.product'])
                     ->where('user_id', auth()->id())
                     ->latest()
                     ->get();

        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $cartItems = Cart::with('product')
                        ->where('user_id', auth()->id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        // التحقق من توفر جميع المنتجات
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->quantity) {
                return back()->with('error', "المنتج {$item->product->name} غير متوفر بالكمية المطلوبة");
            }
        }

        DB::transaction(function () use ($cartItems) {
            // حساب الإجمالي
            $total = $cartItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

            // إضافة الشحن إذا كان أقل من 300
            if ($total < 300) {
                $total += 30;
            }

            // إنشاء الطلب
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $total,
                'status' => 'pending'
            ]);

            // إضافة عناصر الطلب وتحديث المخزون
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                // تحديث المخزون
                $item->product->decrement('quantity', $item->quantity);
            }

            // حذف السلة
            Cart::where('user_id', auth()->id())->delete();
        });

        return redirect()->route('orders.index')->with('success', 'تم إنشاء الطلب بنجاح');
    }
}

```

### 8. Http\Controllers\ProductController.php

**نوع:** php | **الحجم:** 1,288 بايت

```php
<?php
// app/Http/Controllers/ProductController.php (Frontend)
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->get();

        $query = Product::with('category')->where('quantity', '>', 0);

        // البحث حسب الفئة
        if ($request->has('category') && $request->category) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // البحث النصي
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(12);

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}

```

### 9. Http\Controllers\ProfileController.php

**نوع:** php | **الحجم:** 1,416 بايت

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

```

### 10. Http\Controllers\Admin\CategoryController.php

**نوع:** php | **الحجم:** 3,325 بايت

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
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

    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // البحث
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        $slug = Str::slug($request->name);

        // التحقق من فرادة الـ slug
        $originalSlug = $slug;
        $count = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'تم إنشاء الفئة بنجاح');
    }

    public function show(Category $category)
    {
        $category->load('products');
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $slug = Str::slug($request->name);

        // التحقق من فرادة الـ slug
        if ($request->name !== $category->name) {
            $originalSlug = $slug;
            $count = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
        } else {
            $slug = $category->slug; // الاحتفاظ بالـ slug الحالي
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث الفئة بنجاح');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'لا يمكن حذف فئة تحتوي على منتجات');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'تم حذف الفئة بنجاح');
    }
}

```

### 11. Http\Controllers\Admin\DashboardController.php

**نوع:** php | **الحجم:** 12,696 بايت

```php
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

```

### 12. Http\Controllers\Admin\OrderController.php

**نوع:** php | **الحجم:** 5,234 بايت

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
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

    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // فلترة حسب الحالة
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // فلترة حسب العميل
        if ($request->has('customer') && $request->customer != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%')
                  ->orWhere('email', 'like', '%' . $request->customer . '%');
            });
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15);

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product.category']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered'
        ]);

        try {
            // تسجيل محاولة التحديث
            Log::info('محاولة تحديث حالة الطلب', [
                'order_id' => $order->id,
                'old_status' => $order->status,
                'new_status' => $request->status,
                'user_id' => auth()->id(),
                'admin_name' => auth()->user()->name
            ]);

            $oldStatus = $order->status;

            // تحديث الحالة
            $order->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

            // تأكيد التحديث
            $order->refresh();

            Log::info('تم تحديث حالة الطلب بنجاح', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $order->status,
                'updated_at' => $order->updated_at
            ]);

            $statusNames = [
                'pending' => 'في الانتظار',
                'processing' => 'قيد المعالجة',
                'shipped' => 'تم الشحن',
                'delivered' => 'تم التسليم'
            ];

            $message = sprintf('تم تحديث حالة الطلب من "%s" إلى "%s" بنجاح',
                             $statusNames[$oldStatus] ?? $oldStatus,
                             $statusNames[$order->status] ?? $order->status);

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('خطأ في تحديث حالة الطلب', [
                'order_id' => $order->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث حالة الطلب: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        try {
            // إرجاع المنتجات للمخزون قبل الحذف
            foreach ($order->orderItems as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            $order->delete();

            return redirect()->route('admin.orders.index')->with('success', 'تم حذف الطلب بنجاح وإرجاع المنتجات للمخزون');
        } catch (\Exception $e) {
            Log::error('خطأ في حذف الطلب', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الطلب');
        }
    }
}

```

### 13. Http\Controllers\Admin\ProductController.php

**نوع:** php | **الحجم:** 10,283 بايت

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Product::with('category');

        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // فلترة حسب الفئة
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // فلترة حسب المخزون
        if ($request->has('stock') && $request->stock) {
            switch ($request->stock) {
                case 'available':
                    $query->where('quantity', '>', 10);
                    break;
                case 'low':
                    $query->whereBetween('quantity', [1, 10]);
                    break;
                case 'out':
                    $query->where('quantity', 0);
                    break;
            }
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return redirect()->route('admin.categories.create')
                           ->with('info', 'يجب إنشاء فئة واحدة على الأقل قبل إضافة منتج');
        }

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:10',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ], [
            'name.required' => 'اسم المنتج مطلوب',
            'name.max' => 'اسم المنتج لا يجب أن يتجاوز 255 حرف',
            'category_id.required' => 'الفئة مطلوبة',
            'category_id.exists' => 'الفئة المحددة غير موجودة',
            'description.required' => 'وصف المنتج مطلوب',
            'description.min' => 'وصف المنتج يجب أن يكون 10 أحرف على الأقل',
            'price.required' => 'سعر المنتج مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر يجب أن يكون أكبر من صفر',
            'quantity.required' => 'كمية المخزون مطلوبة',
            'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
            'quantity.min' => 'الكمية لا يمكن أن تكون أقل من صفر',
            'image.image' => 'الملف يجب أن يكون صورة',
            'image.mimes' => 'نوع الصورة يجب أن يكون: jpeg, png, jpg, gif, webp',
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز 2MB'
        ]);

        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);

            // التأكد من فرادة الـ slug
            $originalSlug = $data['slug'];
            $count = 1;
            while (Product::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $count;
                $count++;
            }

            // رفع الصورة
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $data['image'] = $image->storeAs('products', $imageName, 'public');
            }

            Product::create($data);

            return redirect()->route('admin.products.index')->with('success', 'تم إنشاء المنتج بنجاح!');

        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء المنتج', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء المنتج: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'orderItems.order.user', 'carts.user']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:10',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ], [
            'name.required' => 'اسم المنتج مطلوب',
            'name.max' => 'اسم المنتج لا يجب أن يتجاوز 255 حرف',
            'category_id.required' => 'الفئة مطلوبة',
            'category_id.exists' => 'الفئة المحددة غير موجودة',
            'description.required' => 'وصف المنتج مطلوب',
            'description.min' => 'وصف المنتج يجب أن يكون 10 أحرف على الأقل',
            'price.required' => 'سعر المنتج مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر يجب أن يكون أكبر من صفر',
            'quantity.required' => 'كمية المخزون مطلوبة',
            'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
            'quantity.min' => 'الكمية لا يمكن أن تكون أقل من صفر',
            'image.image' => 'الملف يجب أن يكون صورة',
            'image.mimes' => 'نوع الصورة يجب أن يكون: jpeg, png, jpg, gif, webp',
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز 2MB'
        ]);

        try {
            $data = $request->all();

            // تحديث الـ slug إذا تغير الاسم
            if ($request->name !== $product->name) {
                $data['slug'] = Str::slug($request->name);

                // التأكد من فرادة الـ slug
                $originalSlug = $data['slug'];
                $count = 1;
                while (Product::where('slug', $data['slug'])->where('id', '!=', $product->id)->exists()) {
                    $data['slug'] = $originalSlug . '-' . $count;
                    $count++;
                }
            }

            // معالجة الصورة
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                // رفع الصورة الجديدة
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $data['image'] = $image->storeAs('products', $imageName, 'public');
            }

            $product->update($data);

            return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح!');

        } catch (\Exception $e) {
            Log::error('خطأ في تحديث المنتج', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث المنتج: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            // التحقق من وجود طلبات على هذا المنتج
            if ($product->orderItems()->exists()) {
                return back()->with('error', 'لا يمكن حذف منتج تم طلبه من قبل. يمكنك تعيين الكمية إلى صفر بدلاً من ذلك.');
            }

            // حذف الصورة
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // حذف العناصر من السلة
            $product->carts()->delete();

            // حذف المنتج
            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح!');

        } catch (\Exception $e) {
            Log::error('خطأ في حذف المنتج', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'حدث خطأ أثناء حذف المنتج: ' . $e->getMessage());
        }
    }
}

```

### 14. Http\Controllers\Admin\UserController.php

**نوع:** php | **الحجم:** 3,747 بايت

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

    public function index(Request $request)
    {
        $query = User::query();

        // فلترة حسب الدور
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // البحث حسب الاسم أو البريد
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->withCount(['orders', 'carts'])->latest()->paginate(15);

        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'customers' => User::where('role', 'customer')->count(),
            'recent' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['orders.orderItems.product', 'carts.product']);
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,customer',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,customer',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }
}

```

### 15. Http\Controllers\Api\ProductController.php

**نوع:** php | **الحجم:** 606 بايت

```php
<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return response()->json($products);
    }

    public function show(Product $product)
    {
        $product->load('category');
        return response()->json($product);
    }

    public function categories()
    {
        $categories = Category::with('products')->get();
        return response()->json($categories);
    }
}

```

### 16. Http\Controllers\Auth\AuthController.php

**نوع:** php | **الحجم:** 1,743 بايت

```php
<?php
// app/Http/Controllers/Auth/AuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

```

### 17. Http\Controllers\Auth\AuthenticatedSessionController.php

**نوع:** php | **الحجم:** 1,069 بايت

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

```

### 18. Http\Controllers\Auth\ConfirmablePasswordController.php

**نوع:** php | **الحجم:** 1,055 بايت

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}

```

### 19. Http\Controllers\Auth\EmailVerificationNotificationController.php

**نوع:** php | **الحجم:** 661 بايت

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}

```

### 20. Http\Controllers\Auth\EmailVerificationPromptController.php

**نوع:** php | **الحجم:** 595 بايت

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : view('auth.verify-email');
    }
}

```

### 21. Http\Controllers\Auth\NewPasswordController.php

**نوع:** php | **الحجم:** 2,188 بايت

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}

```

### 22. Http\Controllers\Auth\PasswordController.php

**نوع:** php | **الحجم:** 795 بايت

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}

```

### 23. Http\Controllers\Auth\PasswordResetLinkController.php

**نوع:** php | **الحجم:** 1,315 بايت

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}

```

### 24. Http\Controllers\Auth\RegisteredUserController.php

**نوع:** php | **الحجم:** 1,351 بايت

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}

```

### 25. Http\Controllers\Auth\VerifyEmailController.php

**نوع:** php | **الحجم:** 822 بايت

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}

```

### 26. Http\Middleware\Authenticate.php

**نوع:** php | **الحجم:** 469 بايت

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}

```

### 27. Http\Middleware\EncryptCookies.php

**نوع:** php | **الحجم:** 307 بايت

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}

```

### 28. Http\Middleware\IsAdmin.php

**نوع:** php | **الحجم:** 419 بايت

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        return $next($request);
    }
}

```

### 29. Http\Middleware\PreventRequestsDuringMaintenance.php

**نوع:** php | **الحجم:** 366 بايت

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}

```

### 30. Http\Middleware\RedirectIfAuthenticated.php

**نوع:** php | **الحجم:** 877 بايت

```php
<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}

```

### 31. Http\Middleware\TrimStrings.php

**نوع:** php | **الحجم:** 381 بايت

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}

```

### 32. Http\Middleware\TrustHosts.php

**نوع:** php | **الحجم:** 372 بايت

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts()
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}

```

### 33. Http\Middleware\TrustProxies.php

**نوع:** php | **الحجم:** 649 بايت

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}

```

### 34. Http\Middleware\VerifyCsrfToken.php

**نوع:** php | **الحجم:** 320 بايت

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}

```

### 35. Http\Requests\ProfileUpdateRequest.php

**نوع:** php | **الحجم:** 568 بايت

```php
<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
}

```

### 36. Http\Requests\Auth\LoginRequest.php

**نوع:** php | **الحجم:** 2,215 بايت

```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}

```

### 37. Models\Cart.php

**نوع:** php | **الحجم:** 1,028 بايت

```php
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

```

### 38. Models\Category.php

**نوع:** php | **الحجم:** 1,247 بايت

```php
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

```

### 39. Models\Order.php

**نوع:** php | **الحجم:** 2,482 بايت

```php
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

```

### 40. Models\OrderItem.php

**نوع:** php | **الحجم:** 793 بايت

```php
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

```

### 41. Models\Product.php

**نوع:** php | **الحجم:** 3,125 بايت

```php
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

```

### 42. Models\User.php

**نوع:** php | **الحجم:** 2,528 بايت

```php
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
        'password' => 'hashed',
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

```

### 43. Providers\AppServiceProvider.php

**نوع:** php | **الحجم:** 403 بايت

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

```

### 44. Providers\AuthServiceProvider.php

**نوع:** php | **الحجم:** 613 بايت

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}

```

### 45. Providers\BroadcastServiceProvider.php

**نوع:** php | **الحجم:** 380 بايت

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}

```

### 46. Providers\EventServiceProvider.php

**نوع:** php | **الحجم:** 725 بايت

```php
<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

```

### 47. Providers\RouteServiceProvider.php

**نوع:** php | **الحجم:** 1,676 بايت

```php
<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

```

### 48. View\Components\AppLayout.php

**نوع:** php | **الحجم:** 296 بايت

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}

```

### 49. View\Components\GuestLayout.php

**نوع:** php | **الحجم:** 300 بايت

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}

```

---

**ملاحظة للذكاء الاصطناعي:**
هذا المشروع تم تحضيره خصيصاً لتحليلك. يمكنك تحليل الكود، اقتراح تحسينات، أو الإجابة على أسئلة محددة حوله.
