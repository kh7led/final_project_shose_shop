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
