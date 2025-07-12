<!-- resources/views/admin/users/show.blade.php -->
@extends('layouts.app')

@section('title', 'تفاصيل المستخدم - ' . $user->name)

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-user me-2"></i>تفاصيل المستخدم: {{ $user->name }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>تعديل
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للمستخدمين
            </a>
        </div>
    </div>

    <div class="row">
        <!-- معلومات المستخدم -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">المعلومات الأساسية</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-circle mx-auto mb-2" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <h4>{{ $user->name }}</h4>
                        <span class="badge {{ $user->role == 'admin' ? 'bg-warning' : 'bg-success' }}">
                            {{ $user->role == 'admin' ? 'مدير' : 'عميل' }}
                        </span>
                    </div>

                    <hr>

                    <p><strong>البريد الإلكتروني:</strong><br>{{ $user->email }}</p>
                    <p><strong>تاريخ التسجيل:</strong><br>{{ $user->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>آخر تحديث:</strong><br>{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">الإحصائيات</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-primary">{{ $user->orders->count() }}</div>
                                <div class="stat-label">طلب</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-info">{{ $user->carts->count() }}</div>
                                <div class="stat-label">منتج في السلة</div>
                            </div>
                        </div>
                        <div class="col-6 mt-3">
                            <div class="stat-item">
                                <div class="stat-number text-success">
                                    {{ number_format($user->orders->sum('total_price'), 2) }}
                                </div>
                                <div class="stat-label">إجمالي المشتريات (شيقل)</div>
                            </div>
                        </div>
                        <div class="col-6 mt-3">
                            <div class="stat-item">
                                <div class="stat-number text-warning">
                                    {{ $user->orders->count() > 0 ? number_format($user->orders->avg('total_price'), 2) : 0 }}
                                </div>
                                <div class="stat-label">متوسط الطلب (شيقل)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الطلبات والسلة -->
        <div class="col-lg-8">
            <!-- الطلبات -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">الطلبات ({{ $user->orders->count() }})</h5>
                    @if($user->orders->count() > 5)
                        <small class="text-muted">آخر 5 طلبات</small>
                    @endif
                </div>
                <div class="card-body">
                    @if($user->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>رقم الطلب</th>
                                        <th>المنتجات</th>
                                        <th>المبلغ</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                        <th>إجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders->take(5) as $order)
                                        <tr>
                                            <td><strong>#{{ $order->id }}</strong></td>
                                            <td>{{ $order->orderItems->sum('quantity') }} منتج</td>
                                            <td>{{ number_format($order->total_price, 2) }} شيقل</td>
                                            <td>
                                                <span class="badge
                                                    @switch($order->status)
                                                        @case('pending') bg-warning @break
                                                        @case('processing') bg-info @break
                                                        @case('shipped') bg-primary @break
                                                        @case('delivered') bg-success @break
                                                        @default bg-secondary
                                                    @endswitch">
                                                    @switch($order->status)
                                                        @case('pending') في الانتظار @break
                                                        @case('processing') قيد المعالجة @break
                                                        @case('shipped') تم الشحن @break
                                                        @case('delivered') تم التسليم @break
                                                        @default {{ $order->status }}
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-receipt fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">لا توجد طلبات</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- السلة الحالية -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">السلة الحالية ({{ $user->carts->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($user->carts->count() > 0)
                        @foreach($user->carts as $item)
                            <div class="d-flex align-items-center py-2 border-bottom">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                         class="rounded me-3" alt="{{ $item->product->name }}"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-shoe-prints text-muted"></i>
                                    </div>
                                @endif

                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <small class="text-muted">الكمية: {{ $item->quantity }}</small>
                                </div>

                                <div class="text-end">
                                    <div class="fw-bold">{{ number_format($item->product->price * $item->quantity, 2) }} شيقل</div>
                                    <small class="text-muted">{{ number_format($item->product->price, 2) }} شيقل للقطعة</small>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-end mt-3">
                            <strong>إجمالي السلة: {{ number_format($user->carts->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }}شيقل</strong>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">السلة فارغة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    background: linear-gradient(45deg, #007bff, #6610f2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.stat-item {
    padding: 10px 5px;
}

.stat-number {
    font-size: 1.5rem;
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
