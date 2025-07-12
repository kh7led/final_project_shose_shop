@extends('layouts.app')

@section('title', $product->name . ' - متجر الأحذية')

@section('content')
<div class="container py-5">
    <!-- شريط التنقل -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}"><i class="fas fa-home me-1"></i>الرئيسية</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.index') }}">المنتجات</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- صورة المنتج -->
        <div class="col-lg-6">
            <div class="product-image-container mb-4">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         class="img-fluid rounded shadow"
                         alt="{{ $product->name }}"
                         style="width: 100%; height: 500px; object-fit: cover;">
                @else
                    <div class="bg-light rounded shadow d-flex align-items-center justify-content-center"
                         style="width: 100%; height: 500px;">
                        <div class="text-center">
                            <i class="fas fa-image fa-5x text-muted mb-3"></i>
                            <p class="text-muted">لا توجد صورة متاحة</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- تفاصيل المنتج -->
        <div class="col-lg-6">
            <div class="product-details">
                <!-- عنوان المنتج -->
                <h1 class="product-title mb-3">{{ $product->name }}</h1>

                <!-- الفئة -->
                <div class="mb-3">
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                       class="badge bg-primary text-decoration-none fs-6">
                        <i class="fas fa-tag me-1"></i>{{ $product->category->name }}
                    </a>
                </div>

                <!-- السعر -->
                <div class="price-section mb-4">
                    <h2 class="text-primary mb-0">{{ number_format($product->price) }} شيقل</h2>
                </div>

                <!-- حالة المخزون -->
                <div class="stock-status mb-4">
                    @if($product->quantity > 10)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>متوفر في المخزون</strong> - {{ $product->quantity }} قطعة
                        </div>
                    @elseif($product->quantity > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>كمية قليلة!</strong> - متبقي {{ $product->quantity }} قطعة فقط
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>نفذت الكمية</strong> - غير متوفر حالياً
                        </div>
                    @endif
                </div>

                <!-- الوصف -->
                @if($product->description)
                    <div class="description-section mb-4">
                        <h5><i class="fas fa-info-circle me-2"></i>وصف المنتج</h5>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>
                @endif

                <!-- قسم الشراء -->
                @auth
                    @if($product->quantity > 0)
                        <div class="purchase-section">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-shopping-cart me-2"></i>إضافة للسلة
                                    </h5>

                                    <!-- محدد الكمية -->
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">الكمية:</label>
                                        <select id="quantity" class="form-select" style="max-width: 150px;">
                                            @for($i = 1; $i <= min(10, $product->quantity); $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <!-- زر الإضافة -->
                                    <div class="d-grid">
                                        <button type="button"
                                                class="btn btn-primary btn-lg add-to-cart-btn"
                                                data-product-id="{{ $product->id }}"
                                                data-quantity="1">
                                            <i class="fas fa-cart-plus me-2"></i>إضافة للسلة
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="purchase-section">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="text-muted">
                                        <i class="fas fa-times-circle me-2"></i>غير متوفر حالياً
                                    </h5>
                                    <p class="text-muted mb-3">هذا المنتج نفذت كميته من المخزون</p>
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-arrow-right me-2"></i>تصفح منتجات أخرى
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="purchase-section">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5><i class="fas fa-user me-2"></i>مطلوب تسجيل الدخول</h5>
                                <p class="text-muted mb-3">يجب تسجيل الدخول أولاً لتتمكن من إضافة المنتجات للسلة</p>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
                                    </a>
                                    <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-user-plus me-2"></i>إنشاء حساب جديد
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات إضافية</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-truck me-2 text-primary"></i>شحن مجاني للطلبات أكثر من 300 شيقل</li>
                                <li><i class="fas fa-undo me-2 text-primary"></i>إمكانية الإرجاع خلال 14 يوم</li>
                                <li><i class="fas fa-shield-alt me-2 text-primary"></i>ضمان الجودة</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-phone me-2 text-primary"></i>دعم فني 24/7</li>
                                <li><i class="fas fa-credit-card me-2 text-primary"></i>دفع آمن</li>
                                <li><i class="fas fa-clock me-2 text-primary"></i>توصيل سريع</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- منتجات مشابهة -->
    @if($relatedProducts && $relatedProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4"><i class="fas fa-heart me-2"></i>منتجات مشابهة</h3>
                <div class="row g-3">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-md-3">
                            <div class="card product-card h-100">
                                <div class="position-relative">
                                    @if($relatedProduct->image)
                                        <img src="{{ asset('storage/' . $relatedProduct->image) }}"
                                             class="card-img-top"
                                             alt="{{ $relatedProduct->name }}"
                                             style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">{{ Str::limit($relatedProduct->name, 30) }}</h6>
                                    <p class="text-primary mb-2">{{ number_format($relatedProduct->price) }} شيقل</p>
                                    <div class="mt-auto">
                                        <a href="{{ route('products.show', $relatedProduct->slug) }}"
                                           class="btn btn-outline-primary btn-sm w-100">
                                            عرض التفاصيل
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- JavaScript لربط محدد الكمية -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantitySelect = document.getElementById('quantity');
    const addButton = document.querySelector('.add-to-cart-btn');

    if (quantitySelect && addButton) {
        // ربط تغيير الكمية بالزر
        quantitySelect.addEventListener('change', function() {
            addButton.setAttribute('data-quantity', this.value);
        });

        // تحديد الكمية الأولية
        addButton.setAttribute('data-quantity', quantitySelect.value);
    }
});
</script>
@endsection
