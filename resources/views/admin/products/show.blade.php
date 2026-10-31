<!-- resources/views/admin/products/show.blade.php -->
@extends('layouts.app')

@section('title', 'تفاصيل المنتج - ' . $product->name)

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-shoe-prints me-2"></i>{{ $product->name }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>تعديل
            </a>
            <a href="{{ route('products.show', $product->slug) }}"
               class="btn btn-info" target="_blank">
                <i class="fas fa-external-link-alt me-2"></i>عرض في المتجر
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للمنتجات
            </a>
        </div>
    </div>

    <div class="row">
        <!-- معلومات المنتج -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     class="img-fluid rounded shadow" alt="{{ $product->name }}"
                                     style="width: 100%; height: 300px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded shadow d-flex align-items-center justify-content-center"
                                     style="width: 100%; height: 300px;">
                                    <i class="fas fa-shoe-prints fa-4x text-muted"></i>
                                </div>
                            @endif
                            @if($product->video)
                                <video src="{{ asset('storage/' . $product->video) }}"
                                       controls playsinline preload="metadata"
                                       class="rounded shadow w-100 mt-3" style="max-height: 300px; background: #000;"></video>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h3 class="mb-3">{{ $product->name }}</h3>
                            <p class="text-muted mb-4">{{ $product->description }}</p>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>السعر:</strong><br>
                                    <span class="h4 text-primary">{{ number_format($product->price, 2) }} شيقل </span>
                                </div>
                                <div class="col-6">
                                    <strong>المخزون:</strong><br>
                                    <span class="badge {{ $product->quantity > 10 ? 'bg-success' : ($product->quantity > 0 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $product->quantity }} متوفر
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>الفئة:</strong><br>
                                    <span class="badge bg-primary">{{ $product->category->name }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>الرابط:</strong><br>
                                    <code>{{ $product->slug }}</code>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <strong>تاريخ الإنشاء:</strong><br>
                                    <small>{{ $product->created_at->format('Y-m-d H:i') }}</small>
                                </div>
                                <div class="col-6">
                                    <strong>آخر تحديث:</strong><br>
                                    <small>{{ $product->updated_at->format('Y-m-d H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإحصائيات والمعلومات الإضافية -->
        <div class="col-lg-4">
            <!-- إحصائيات المبيعات -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">إحصائيات المبيعات</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-success">{{ $product->orderItems->sum('quantity') }}</div>
                                <div class="stat-label">إجمالي المبيعات</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-info">{{ $product->orderItems->count() }}</div>
                                <div class="stat-label">عدد الطلبات</div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="stat-item">
                                <div class="stat-number text-primary">
                                    {{ number_format($product->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }}
                                </div>
                                <div class="stat-label">إجمالي الإيرادات (شيقل)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- حالة المخزون -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">حالة المخزون</h5>
                </div>
                <div class="card-body">
                    @if($product->quantity == 0)
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>نفذت الكمية
                        </div>
                    @elseif($product->quantity < 10)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            مخزون قليل ({{ $product->quantity }} قطعة)
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            مخزون جيد ({{ $product->quantity }} قطعة)
                        </div>
                    @endif

                    <div class="progress mb-3">
                        @php
                            $maxStock = 100; // افتراض الحد الأقصى للمخزون
                            $percentage = min(($product->quantity / $maxStock) * 100, 100);
                        @endphp
                        <div class="progress-bar {{ $product->quantity < 10 ? 'bg-danger' : ($product->quantity < 30 ? 'bg-warning' : 'bg-success') }}"
                             style="width: {{ $percentage }}%">
                        </div>
                    </div>

                    <small class="text-muted">
                        متوفر: {{ $product->quantity }} من أصل {{ $maxStock }} (تقديري)
                    </small>
                </div>
            </div>

            <!-- السلة الحالية -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">في سلة التسوق</h5>
                </div>
                <div class="card-body">
                    @if($product->carts->count() > 0)
                        <p class="mb-2">
                            <strong>{{ $product->carts->sum('quantity') }}</strong> قطعة في سلال
                            <strong>{{ $product->carts->count() }}</strong> عميل
                        </p>

                        <div class="list-group list-group-flush">
                            @foreach($product->carts->take(3) as $cart)
                                <div class="list-group-item px-0 py-2">
                                    <small>
                                        {{ $cart->user->name }} - {{ $cart->quantity }} قطع
                                        <span class="text-muted">({{ $cart->updated_at->diffForHumans() }})</span>
                                    </small>
                                </div>
                            @endforeach
                        </div>

                        @if($product->carts->count() > 3)
                            <small class="text-muted">و {{ $product->carts->count() - 3 }} عملاء آخرين</small>
                        @endif
                    @else
                        <p class="text-muted mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>
                            لا يوجد في أي سلة حالياً
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- الطلبات الحديثة -->
    @if($product->orderItems->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">آخر الطلبات</h5>
                    <small class="text-muted">آخر 5 طلبات</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>العميل</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th>الإجمالي</th>
                                    <th>التاريخ</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->orderItems->take(5) as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $item->order) }}">
                                                #{{ $item->order->id }}
                                            </a>
                                        </td>
                                        <td>{{ $item->order->user->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price, 2) }}شيقل</td>
                                        <td><strong>{{ number_format($item->price * $item->quantity, 2) }}شيقل</strong></td>
                                        <td>{{ $item->order->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->order->status_color }}">
                                                {{ $item->order->status_text }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.stat-item {
    padding: 15px 10px;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: bold;
    line-height: 1;
}

.stat-label {
    color: #6c757d;
    font-weight: 500;
    font-size: 0.9rem;
}
</style>
@endsection
