<!-- resources/views/errors/404.blade.php -->
@extends('layouts.app')

@section('title', 'الصفحة غير موجودة - 404')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-page">
                <div class="error-icon mb-4">
                    <i class="fas fa-search fa-6x text-muted"></i>
                </div>

                <h1 class="display-1 fw-bold text-primary">404</h1>
                <h2 class="mb-4">الصفحة غير موجودة</h2>
                <p class="lead text-muted mb-4">
                    عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها إلى مكان آخر.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i>العودة للرئيسية
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>تصفح المنتجات
                    </a>
                </div>

                <!-- اقتراحات للبحث -->
                <div class="mt-5">
                    <h5 class="mb-3">قد تكون تبحث عن:</h5>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                            جميع المنتجات
                        </a>
                        @if(auth()->check())
                            <a href="{{ route('cart.index') }}" class="btn btn-sm btn-outline-secondary">
                                سلة التسوق
                            </a>
                            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary">
                                طلباتي
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">
                                تسجيل الدخول
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-sm btn-outline-secondary">
                                إنشاء حساب
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    animation: fadeIn 0.8s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.error-icon {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}
</style>
@endsection
