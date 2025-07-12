@extends('layouts.app')

@section('title', 'متجر الأحذية - الصفحة الرئيسية')

@section('content')
<!-- البانر الرئيسي -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">أحدث صيحات الأحذية</h1>
                <p class="lead mb-4">اكتشف مجموعتنا الواسعة من الأحذية العصرية والمريحة لجميع المناسبات</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>تسوق الآن
                    </a>
                    <a href="#categories" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-list me-2"></i>الفئات
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-stats row g-3">
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-10 rounded p-3">
                            <h3 class="fw-bold">{{ number_format($stats['total_products']) }}+</h3>
                            <p class="mb-0">منتج متنوع</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-10 rounded p-3">
                            <h3 class="fw-bold">{{ number_format($stats['happy_customers']) }}+</h3>
                            <p class="mb-0">عميل سعيد</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-10 rounded p-3">
                            <h3 class="fw-bold">{{ $stats['total_categories'] }}+</h3>
                            <p class="mb-0">فئة مختلفة</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-10 rounded p-3">
                            <h3 class="fw-bold">{{ number_format($stats['orders_completed']) }}+</h3>
                            <p class="mb-0">طلب مكتمل</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- الفئات -->
@if($categories->count() > 0)
<section id="categories" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">تسوق حسب الفئة</h2>
            <p class="text-muted">اختر من مجموعتنا المتنوعة من فئات الأحذية</p>
        </div>

        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                        <div class="category-card card h-100 text-center">
                            <div class="card-body">
                                <div class="category-icon mb-3">
                                    <i class="fas fa-shoe-prints fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title">{{ $category->name }}</h5>
                                <p class="text-muted">{{ $category->products_count }} منتج متوفر</p>
                                <div class="mt-3">
                                    <span class="btn btn-outline-primary">تسوق الآن</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- المنتجات المميزة -->
@if($featuredProducts->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">المنتجات المميزة</h2>
            <p class="text-muted">أفضل ما لدينا من منتجات عالية الجودة</p>
        </div>

        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 product-card" data-product-id="{{ $product->id }}">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="card-img-top product-image" alt="{{ $product->name }}">
                        @else
                            <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                <i class="fas fa-shoe-prints fa-3x text-muted"></i>
                            </div>
                        @endif

                        <!-- شارة المخزون -->
                        @if($product->quantity <= 5 && $product->quantity > 0)
                            <span class="stock-badge low-stock">متبقي {{ $product->quantity }}</span>
                        @elseif($product->quantity == 0)
                            <span class="stock-badge out-of-stock">نفذ المخزون</span>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-primary mb-2 align-self-start">{{ $product->category->name }}</span>
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($product->description, 80) }}</p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="price-tag">{{ number_format($product->price, 2) }}شيقل</span>
                                    <small class="text-muted">
                                        <i class="fas fa-box"></i> {{ $product->quantity }} متوفر
                                    </small>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-2"></i>عرض التفاصيل
                                    </a>
                                    @auth
                                        @if($product->quantity > 0)
                                            <!-- الزر الجديد المُصلح لـ AJAX -->
                                            <button type="button"
                                                    class="btn btn-primary w-100 add-to-cart-btn"
                                                    data-product-id="{{ $product->id }}"
                                                    data-url="{{ route('cart.add', $product->id) }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-quantity="1">
                                                <i class="fas fa-cart-plus me-2"></i>إضافة للسلة
                                            </button>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>
                                                <i class="fas fa-times me-2"></i>نفذت الكمية
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                            <i class="fas fa-sign-in-alt me-2"></i>سجل دخولك للشراء
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- الأكثر مبيعاً -->
@if($topSellingProducts->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">الأكثر مبيعاً</h2>
            <p class="text-muted">المنتجات التي يختارها عملاؤنا أكثر من غيرها</p>
        </div>

        <div class="row g-4">
            @foreach($topSellingProducts as $index => $product)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 product-card position-relative" data-product-id="{{ $product->id }}">
                        @if($index < 3)
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge
                                    @if($index == 0) bg-warning
                                    @elseif($index == 1) bg-secondary
                                    @else bg-dark @endif">
                                    #{{ $index + 1 }}
                                </span>
                            </div>
                        @endif

                        <!-- شارة المخزون -->
                        @if($product->quantity <= 5 && $product->quantity > 0)
                            <span class="stock-badge low-stock">متبقي {{ $product->quantity }}</span>
                        @elseif($product->quantity == 0)
                            <span class="stock-badge out-of-stock">نفذ المخزون</span>
                        @endif

                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="card-img-top product-image" alt="{{ $product->name }}">
                        @else
                            <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                <i class="fas fa-shoe-prints fa-3x text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-primary mb-2 align-self-start">{{ $product->category->name }}</span>
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($product->description, 80) }}</p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="price-tag">{{ number_format($product->price, 2) }}شيقل</span>
                                    <small class="text-success">
                                        <i class="fas fa-fire"></i> {{ $product->total_sold ?? 0 }} مبيع
                                    </small>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-2"></i>عرض التفاصيل
                                    </a>
                                    @auth
                                        @if($product->quantity > 0)
                                            <!-- الزر الجديد المُصلح لـ AJAX -->
                                            <button type="button"
                                                    class="btn btn-primary w-100 add-to-cart-btn"
                                                    data-product-id="{{ $product->id }}"
                                                    data-url="{{ route('cart.add', $product->id) }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-quantity="1">
                                                <i class="fas fa-cart-plus me-2"></i>إضافة للسلة
                                            </button>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>
                                                <i class="fas fa-times me-2"></i>نفذت الكمية
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                            <i class="fas fa-sign-in-alt me-2"></i>سجل دخولك للشراء
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- أحدث المنتجات -->
@if($latestProducts->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">أحدث المنتجات</h2>
            <p class="text-muted">آخر ما وصل إلى متجرنا من منتجات جديدة</p>
        </div>

        <div class="row g-4">
            @foreach($latestProducts as $product)
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 product-card position-relative" data-product-id="{{ $product->id }}">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-success">جديد</span>
                        </div>

                        <!-- شارة المخزون -->
                        @if($product->quantity <= 5 && $product->quantity > 0)
                            <span class="stock-badge low-stock">متبقي {{ $product->quantity }}</span>
                        @elseif($product->quantity == 0)
                            <span class="stock-badge out-of-stock">نفذ المخزون</span>
                        @endif

                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="card-img-top product-image" alt="{{ $product->name }}">
                        @else
                            <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                <i class="fas fa-shoe-prints fa-3x text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-primary mb-2 align-self-start">{{ $product->category->name }}</span>
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($product->description, 80) }}</p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="price-tag">{{ number_format($product->price, 2) }}شيقل</span>
                                    <small class="text-muted">
                                        <i class="fas fa-box"></i> {{ $product->quantity }} متوفر
                                    </small>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-2"></i>عرض التفاصيل
                                    </a>
                                    @auth
                                        @if($product->quantity > 0)
                                            <!-- الزر الجديد المُصلح لـ AJAX -->
                                            <button type="button"
                                                    class="btn btn-primary w-100 add-to-cart-btn"
                                                    data-product-id="{{ $product->id }}"
                                                    data-url="{{ route('cart.add', $product->id) }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-quantity="1">
                                                <i class="fas fa-cart-plus me-2"></i>إضافة للسلة
                                            </button>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>
                                                <i class="fas fa-times me-2"></i>نفذت الكمية
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                            <i class="fas fa-sign-in-alt me-2"></i>سجل دخولك للشراء
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i>عرض جميع المنتجات
            </a>
        </div>
    </div>
</section>
@endif

<!-- نداء للعمل -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold mb-4">ابدأ رحلة التسوق الآن</h2>
                <p class="lead mb-4">انضم إلى آلاف العملاء السعداء واستمتع بتجربة تسوق فريدة</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>إنشاء حساب جديد
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
                        </a>
                    @else
                        <a href="{{ route('cart.index') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-shopping-cart me-2"></i>عرض السلة
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-receipt me-2"></i>طلباتي
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
}

.hero-section {
    min-height: 70vh;
    display: flex;
    align-items: center;
}

.stat-card {
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.category-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    text-decoration: none;
}

.category-icon {
    transition: all 0.3s ease;
}

.category-card:hover .category-icon {
    transform: scale(1.1);
}

.product-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.product-image {
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.price-tag {
    font-size: 1.2rem;
    font-weight: bold;
    color: #28a745;
}

/* شارات المخزون */
.stock-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 4px 8px;
    border-radius: 10px;
    font-size: 0.75rem;
    z-index: 2;
}

.stock-badge.low-stock {
    background: #ffc107;
    color: #000;
}

.stock-badge.out-of-stock {
    background: #dc3545;
}

/* تحسين أزرار إضافة للسلة */
.add-to-cart-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.add-to-cart-btn.loading {
    color: transparent !important;
}

.add-to-cart-btn.success {
    background: linear-gradient(45deg, #28a745, #20c997) !important;
    border-color: #28a745 !important;
}

@media (max-width: 768px) {
    .hero-section {
        min-height: 60vh;
        text-align: center;
    }

    .display-4 {
        font-size: 2rem;
    }

    .display-5 {
        font-size: 1.5rem;
    }

    .stock-badge {
        top: 5px;
        right: 5px;
        font-size: 0.7rem;
        padding: 2px 6px;
    }
}
</style>

<script>
// تأثير التحميل للمنتجات
document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.product-card');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    productCards.forEach((card) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});

// تشخيص سريع لأزرار السلة
setTimeout(() => {
    const cartButtons = document.querySelectorAll('.add-to-cart-btn');
    console.log(`🎯 تم العثور على ${cartButtons.length} زر إضافة للسلة جديد`);

    cartButtons.forEach((btn, i) => {
        console.log(`   الزر ${i+1}:`, {
            productId: btn.getAttribute('data-product-id'),
            url: btn.getAttribute('data-url'),
            productName: btn.getAttribute('data-product-name')
        });
    });

    if (cartButtons.length > 0) {
        console.log('✅ أزرار السلة جاهزة للعمل!');
    }
}, 2000);
</script>
@endsection
