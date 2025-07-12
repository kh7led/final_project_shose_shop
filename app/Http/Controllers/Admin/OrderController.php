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
