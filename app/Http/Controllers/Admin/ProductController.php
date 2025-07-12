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
                abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
            }
            return $next($request);
        });
    }

    /**
     * عرض قائمة المنتجات في الإدارة
     */
    public function index(Request $request)
    {
        $categories = Category::all();
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
                    $query->where('quantity', '>', 0)->where('quantity', '<=', 10);
                    break;
                case 'out':
                    $query->where('quantity', '=', 0);
                    break;
            }
        }

        $products = $query->latest()->paginate(15);

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * عرض نموذج إضافة منتج جديد
     */
    public function create()
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return redirect()->route('admin.categories.create')
                           ->with('error', 'يجب إضافة فئة واحدة على الأقل قبل إضافة المنتجات');
        }

        return view('admin.products.create', compact('categories'));
    }

    /**
     * حفظ منتج جديد
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
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

            // إنشاء slug فريد
            $data['slug'] = Str::slug($request->name);

            // التأكد من فرادة الـ slug
            $originalSlug = $data['slug'];
            $count = 1;
            while (Product::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $count;
                $count++;
            }

            // رفع الصورة إذا وجدت
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

    /**
     * عرض تفاصيل منتج واحد
     */
    public function show(Product $product)
    {
        $product->load(['category', 'orderItems.order.user', 'carts.user']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * عرض نموذج تعديل المنتج
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return redirect()->route('admin.categories.create')
                           ->with('error', 'لا توجد فئات متاحة. يجب إضافة فئة واحدة على الأقل');
        }

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * تحديث بيانات المنتج
     */
    public function update(Request $request, Product $product)
    {
        // التحقق من صحة البيانات
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

            // التعامل مع الصورة الجديدة
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                // رفع الصورة الجديدة
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $data['image'] = $image->storeAs('products', $imageName, 'public');
            }

            $product->update($data);

            return redirect()->route('admin.products.show', $product)
                           ->with('success', 'تم تحديث المنتج بنجاح!');

        } catch (\Exception $e) {
            Log::error('خطأ في تحديث المنتج', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث المنتج: ' . $e->getMessage());
        }
    }

    /**
     * حذف المنتج
     */
    public function destroy(Product $product)
    {
        try {
            // التحقق من وجود طلبات مرتبطة
            if ($product->orderItems()->count() > 0) {
                return redirect()->back()->with('error', 'لا يمكن حذف منتج مرتبط بطلبات موجودة');
            }

            // حذف الصورة
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // حذف المنتج من السلة أولاً
            $product->carts()->delete();

            // حذف المنتج
            $product->delete();

            return redirect()->route('admin.products.index')
                           ->with('success', 'تم حذف المنتج بنجاح');

        } catch (\Exception $e) {
            Log::error('خطأ في حذف المنتج', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المنتج');
        }
    }
}
