@extends('layouts.app')

@section('title', 'تفاصيل الطلب #' . $order->id)

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>تفاصيل الطلب #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للطلبات
        </a>
    </div>

    <!-- رسائل النجاح والخطأ -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- معلومات الطلب -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">منتجات الطلب</h5>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                        <div class="row align-items-center py-3 border-bottom">
                            <div class="col-md-2">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                         class="img-fluid rounded" alt="{{ $item->product->name }}"
                                         style="height: 60px; width: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="height: 60px; width: 60px;">
                                        <i class="fas fa-shoe-prints text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-5">
                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                <small class="text-muted">{{ $item->product->category->name }}</small>
                            </div>
                            <div class="col-md-2">
                                <span class="badge bg-secondary">الكمية: {{ $item->quantity }}</span>
                            </div>
                            <div class="col-md-3 text-end">
                                <div>{{ number_format($item->price, 2) }} شيقل للقطعة</div>
                                <strong>{{ number_format($item->price * $item->quantity, 2) }} شيقل</strong>
                            </div>
                        </div>
                    @endforeach

                    <div class="row mt-3">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>المجموع الفرعي:</span>
                                <span>{{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }} شيقل</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>الشحن:</span>
                                <span>مجاني</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>الإجمالي:</strong>
                                <strong class="text-primary">{{ number_format($order->total_price, 2) }} شيقل</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات العميل والطلب -->
        <div class="col-lg-4">
            <!-- معلومات العميل -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">معلومات العميل</h5>
                </div>
                <div class="card-body">
                    <p><strong>الاسم:</strong> {{ $order->user->name }}</p>
                    <p><strong>البريد:</strong> {{ $order->user->email }}</p>
                    <p><strong>تاريخ التسجيل:</strong> {{ $order->user->created_at->format('Y-m-d') }}</p>
                    <p><strong>إجمالي الطلبات:</strong> {{ $order->user->orders->count() }} طلب</p>
                    <a href="{{ route('admin.users.show', $order->user) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-user me-1"></i>عرض ملف العميل
                    </a>
                </div>
            </div>

            <!-- حالة الطلب -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">إدارة حالة الطلب</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">الحالة الحالية:</label>
                        <div>
                            <span class="badge bg-{{ $order->status_color }} fs-6">
                                {{ $order->status_text }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" id="statusForm">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="status" class="form-label">تغيير الحالة:</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                    في الانتظار
                                </option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                    قيد المعالجة
                                </option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                    تم الشحن
                                </option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                    تم التسليم
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="updateBtn">
                            <i class="fas fa-save me-2"></i>تحديث الحالة
                        </button>
                    </form>

                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>
                        سيتم حفظ تغيير الحالة فوراً
                    </small>
                </div>
            </div>

            <!-- معلومات الطلب -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">تفاصيل الطلب</h5>
                </div>
                <div class="card-body">
                    <p><strong>رقم الطلب:</strong> #{{ $order->id }}</p>
                    <p><strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>آخر تحديث:</strong> {{ $order->updated_at->format('Y-m-d H:i') }}</p>
                    <p><strong>عدد المنتجات:</strong> {{ $order->orderItems->sum('quantity') }} منتج</p>
                    <p><strong>المبلغ الإجمالي:</strong> {{ number_format($order->total_price, 2) }} شيقل</p>

                    <hr>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-info" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>طباعة الطلب
                        </button>
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100"
                                    onclick="return confirm('هل أنت متأكد من حذف هذا الطلب؟ سيتم إرجاع المنتجات للمخزون.')">
                                <i class="fas fa-trash me-2"></i>حذف الطلب
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusForm = document.getElementById('statusForm');
    const updateBtn = document.getElementById('updateBtn');
    const statusSelect = document.getElementById('status');
    const originalStatus = '{{ $order->status }}';

    // تفعيل/تعطيل زر التحديث حسب التغيير
    statusSelect.addEventListener('change', function() {
        if (this.value !== originalStatus) {
            updateBtn.disabled = false;
            updateBtn.classList.remove('btn-secondary');
            updateBtn.classList.add('btn-primary');
        } else {
            updateBtn.disabled = true;
            updateBtn.classList.remove('btn-primary');
            updateBtn.classList.add('btn-secondary');
        }
    });

    // تأكيد قبل التحديث
    statusForm.addEventListener('submit', function(e) {
        const selectedOption = statusSelect.options[statusSelect.selectedIndex];
        const newStatusText = selectedOption.text;

        if (!confirm(`هل أنت متأكد من تغيير حالة الطلب إلى "${newStatusText}"؟`)) {
            e.preventDefault();
            return false;
        }

        // تعطيل الزر أثناء الإرسال
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التحديث...';
        updateBtn.disabled = true;
    });

    // تعطيل الزر في البداية إذا لم يتغير شيء
    updateBtn.disabled = true;
    updateBtn.classList.add('btn-secondary');
    updateBtn.classList.remove('btn-primary');
});
</script>

<style>
@media print {
    .btn, .card-header, nav, footer {
        display: none !important;
    }

    .container {
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>
@endsection
