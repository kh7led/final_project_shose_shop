@extends('layouts.app')

@section('title', 'خطأ في الخادم - 500')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-page">
                <div class="error-icon mb-4">
                    <i class="fas fa-exclamation-triangle fa-6x text-danger"></i>
                </div>

                <h1 class="display-1 fw-bold text-danger">500</h1>
                <h2 class="mb-4">خطأ في الخادم</h2>
                <p class="lead text-muted mb-4">
                    عذراً، حدث خطأ في الخادم. يرجى المحاولة مرة أخرى لاحقاً.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i>العودة للرئيسية
                    </a>
                    <button onclick="window.location.reload()" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-redo me-2"></i>إعادة المحاولة
                    </button>
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
</style>
@endsection
