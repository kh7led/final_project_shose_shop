@extends('layouts.app')

@section('title', 'الإدارة - إدارة المنتجات')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-cogs me-2"></i>إدارة المنتجات</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>إضافة منتج جديد
        </a>
    </div>

    <!-- فلاتر البحث -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">البحث</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="ابحث في اسم المنتج أو الوصف..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الفئة</label>
                        <select name="category" class="form-select">
                            <option value="">جميع الفئات</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">المخزون</label>
                        <select name="stock" class="form-select">
                            <option value="">جميع المنتجات</option>
                            <option value="available" {{ request('stock') == 'available' ? 'selected' : '' }}>متوفر</option>
                            <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>قليل</option>
                            <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>نفذ</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>بحث
                            </button>
                        </div>
                    </div>
                </div>

                @if(request()->hasAny(['search', 'category', 'stock']))
                    <div class="row mt-2">
                        <div class="col-12">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>إزالة الفلاتر
                            </a>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>الصورة</th>
                                <th>الاسم</th>
                                <th>الفئة</th>
                                <th>السعر</th>
                                <th>المخزون</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="form-check-input product-checkbox">
                                    </td>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 class="rounded" alt="{{ $product->name }}"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-shoe-prints text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                        <br><code class="small">{{ $product->slug }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $product->category->name }}</span>
                                    </td>
                                    <td class="price-tag">{{ number_format($product->price, 2) }} شيكل</td>
                                    <td>
                                        @if($product->quantity > 10)
                                            <span class="badge bg-success">{{ $product->quantity }} متوفر</span>
                                        @elseif($product->quantity > 0)
                                            <span class="badge bg-warning">{{ $product->quantity }} قليل</span>
                                        @else
                                            <span class="badge bg-danger">نفذت الكمية</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $product->created_at->format('d M, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.products.show', $product) }}"
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('products.show', $product->slug) }}"
                                               class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- إجراءات مجمعة -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <button type="button" class="btn btn-outline-danger" id="bulkDeleteBtn" style="display: none;">
                            <i class="fas fa-trash me-2"></i>حذف المحدد
                        </button>
                    </div>
                    <div>
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>لم يتم العثور على منتجات</h5>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'category', 'stock']))
                            لا توجد منتجات تطابق معايير البحث.
                        @else
                            ابدأ بإضافة منتجك الأول.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'category', 'stock']))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-times me-2"></i>إزالة الفلاتر
                        </a>
                    @endif
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>إضافة منتج
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    // اختيار الكل
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkActions();
        });
    }

    // تحديث حالة الإجراءات المجمعة
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });

    function toggleBulkActions() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkDeleteBtn.style.display = 'inline-block';
        } else {
            bulkDeleteBtn.style.display = 'none';
        }
    }

    // حذف مجمع
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
            if (checkedBoxes.length > 0) {
                if (confirm(`هل أنت متأكد من حذف ${checkedBoxes.length} منتج؟`)) {
                    // إضافة منطق الحذف المجمع هنا
                    console.log('حذف المنتجات المحددة');
                }
            }
        });
    }
});
</script>
@endsection
