@extends('layouts.app')

@section('title', 'المنتجات - متجر الأحذية')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- الفلاتر الجانبية -->
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>تصفية المنتجات</h5>
                </div>
                <div class="card-body">
                    <!-- فلتر البحث -->
                    <div class="mb-4">
                        <label class="form-label">البحث</label>
                        <form method="GET" action="{{ route('products.index') }}">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                       placeholder="ابحث عن الأحذية..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- فلتر الفئات -->
                    <div class="mb-3">
                        <label class="form-label">الفئات</label>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('products.index') }}"
                               class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                                <i class="fas fa-th-large me-2"></i>جميع المنتجات
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('products.index', ['category' => $category->slug, 'search' => request('search')]) }}"
                                   class="list-group-item list-group-item-action {{ request('category') == $category->slug ? 'active' : '' }}">
                                    <i class="fas fa-tag me-2"></i>{{ $category->name }}
                                    <span class="badge bg-secondary float-start">{{ $category->products_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- شبكة المنتجات -->
        <div class="col-lg-9">
            <!-- رأس القسم -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-shoe-prints me-2"></i>
                    @if(request('category'))
                        {{ $categories->where('slug', request('category'))->first()->name ?? 'المنتجات' }}
                    @elseif(request('search'))
                        نتائج البحث عن "{{ request('search') }}"
                    @else
                        جميع المنتجات
                    @endif
                </h2>
                <span class="badge bg-primary fs-6">{{ $products->total() }} منتج</span>
            </div>

            @if($products->count() > 0)
                <!-- شبكة المنتجات -->
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="card product-card h-100 shadow-sm">
                                <div class="position-relative">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             class="card-img-top"
                                             alt="{{ $product->name }}"
                                             style="height: 250px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                             style="height: 250px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif

                                    <!-- شارة المخزون -->
                                    <div class="position-absolute top-0 start-0 m-2">
                                        @if($product->quantity > 10)
                                            <span class="badge bg-success">متوفر</span>
                                        @elseif($product->quantity > 0)
                                            <span class="badge bg-warning">كمية قليلة</span>
                                        @else
                                            <span class="badge bg-danger">نفذت الكمية</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text text-muted small flex-grow-1">
                                        {{ Str::limit($product->description, 80) }}
                                    </p>

                                    <!-- الفئة والسعر -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                        <h5 class="text-primary mb-0">{{ number_format($product->price) }} شيقل</h5>
                                    </div>

                                    <!-- محدد الكمية للمنتجات المتوفرة -->
                                    @if($product->quantity > 0 && auth()->check())
                                        <div class="mb-3">
                                            <label class="form-label small">الكمية:</label>
                                            <select class="form-select form-select-sm quantity-input"
                                                    data-product-id="{{ $product->id }}">
                                                @for($i = 1; $i <= min(5, $product->quantity); $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    @endif

                                    <!-- الأزرار -->
                                    <div class="mt-auto">
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('products.show', $product->slug) }}"
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>عرض التفاصيل
                                            </a>

                                            @auth
                                                @if($product->quantity > 0)
                                                    <button type="button"
                                                            class="btn btn-primary btn-sm add-to-cart-btn"
                                                            data-product-id="{{ $product->id }}"
                                                            data-quantity="1">
                                                        <i class="fas fa-cart-plus me-1"></i>إضافة للسلة
                                                    </button>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-times me-1"></i>نفذت الكمية
                                                    </button>
                                                @endif
                                            @else
                                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-sign-in-alt me-1"></i>سجل دخولك للشراء
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- الترقيم -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <!-- لا توجد منتجات -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-search fa-4x text-muted"></i>
                    </div>
                    <h4>لم يتم العثور على منتجات</h4>
                    <p class="text-muted mb-4">
                        @if(request('search'))
                            لم يتم العثور على منتجات تطابق "{{ request('search') }}"
                        @elseif(request('category'))
                            لا توجد منتجات في هذه الفئة حالياً
                        @else
                            لا توجد منتجات متاحة حالياً
                        @endif
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-home me-1"></i>عرض جميع المنتجات
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-right me-1"></i>العودة للرئيسية
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript لربط محددات الكمية بالأزرار -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ربط محددات الكمية بأزرار الإضافة
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            const productId = e.target.getAttribute('data-product-id');
            const quantity = e.target.value;

            // العثور على زر الإضافة المقابل
            const productCard = e.target.closest('.product-card');
            const addButton = productCard.querySelector('.add-to-cart-btn');

            if (addButton) {
                addButton.setAttribute('data-quantity', quantity);
            }
        }
    });
});
</script>
@endsection
