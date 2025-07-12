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
    // عرض السلة
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
    // إضافة منتج للسلة (AJAX) 🎯
    // ========================================
    public function add(Request $request, $productId)
    {
        try {
            // التحقق من صحة البيانات
            $request->validate([
                'quantity' => 'integer|min:1|max:10'
            ]);

            // العثور على المنتج
            $product = Product::findOrFail($productId);
            $quantity = $request->input('quantity', 1);

            // التحقق من توفر الكمية
            if ($quantity > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكمية المطلوبة غير متوفرة. المتوفر: ' . $product->quantity
                ], 422);
            }

            // البحث عن المنتج في السلة
            $cartItem = Cart::where('user_id', auth()->id())
                           ->where('product_id', $product->id)
                           ->first();

            if ($cartItem) {
                // تحديث الكمية
                $newQuantity = $cartItem->quantity + $quantity;

                if ($newQuantity > $product->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'لا يمكن إضافة هذه الكمية. الحد الأقصى المتاح: ' . ($product->quantity - $cartItem->quantity)
                    ], 422);
                }

                $cartItem->update(['quantity' => $newQuantity]);
                $message = "تم تحديث كمية {$product->name} في السلة";
            } else {
                // إضافة جديدة
                Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ]);
                $message = "تم إضافة {$product->name} للسلة بنجاح";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $this->getCartCount(),
                'product_name' => $product->name
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة'
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير موجود'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Cart add error: ' . $e->getMessage(), [
                'product_id' => $productId,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة المنتج للسلة'
            ], 500);
        }
    }

    // ========================================
    // حذف منتج من السلة (AJAX)
    // ========================================
    public function remove(Request $request, Cart $cart)
    {
        try {
            // التحقق من الملكية
            if ($cart->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مسموح لك بحذف هذا العنصر'
                ], 403);
            }

            $productName = $cart->product->name;
            $cart->delete();

            return response()->json([
                'success' => true,
                'message' => "تم حذف {$productName} من السلة",
                'cart_count' => $this->getCartCount()
            ]);

        } catch (\Exception $e) {
            Log::error('Cart remove error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المنتج'
            ], 500);
        }
    }

    // ========================================
    // تحديث كمية المنتج في السلة
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
    // حذف منتج من السلة (النظام القديم)
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
    // API: الحصول على عدد المنتجات في السلة
    // ========================================
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

    // ========================================
    // API: الحصول على عناصر السلة
    // ========================================
    public function items()
    {
        try {
            $items = Cart::with(['product.category'])
                        ->where('user_id', auth()->id())
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'product_id' => $item->product_id,
                                'product_name' => $item->product->name,
                                'product_price' => $item->product->price,
                                'quantity' => $item->quantity,
                                'total' => $item->product->price * $item->quantity
                            ];
                        });

            return response()->json(['items' => $items]);

        } catch (\Exception $e) {
            Log::error('Cart items error: ' . $e->getMessage());
            return response()->json(['items' => []]);
        }
    }

    // ========================================
    // دوال مساعدة
    // ========================================
    private function getCartCount()
    {
        if (!auth()->check()) {
            return 0;
        }

        return Cart::where('user_id', auth()->id())->sum('quantity');
    }
}
