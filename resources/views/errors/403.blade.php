@extends('layouts.app')

@section('title', 'غير مصرح - 403')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-page">
                <div class="error-icon mb-4">
                    <i class="fas fa-lock fa-6x text-warning"></i>
                </div>

                <h1 class="display-1 fw-bold text-warning">403</h1>
                <h2 class="mb-4">غير مصرح لك بالوصول</h2>
                <p class="lead text-muted mb-4">
                    عذراً، ليس لديك الصلاحية للوصول إلى هذه الصفحة.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i>العودة للرئيسية
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
                        </a>
                    @endguest
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
    animation: shake 1s infinite;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
</style>
@endsection
