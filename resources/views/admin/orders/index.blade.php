@extends('layouts.app')

@section('title', 'إدارة الطلبات - الإدارة')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-shopping-cart me-2"></i>إدارة الطلبات</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للوحة التحكم
            </a>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row g-3 mb-4">
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-primary">{{ $stats['total'] }}</h4>
                    <small>الإجمالي</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-warning">{{ $stats['pending'] }}</h4>
                    <small>في الانتظار</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-info">{{ $stats['processing'] }}</h4>
                    <small>قيد المعالجة</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-primary">{{ $stats['shipped'] }}</h4>
                    <small>تم الشحن</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-success">{{ $stats['delivered'] }}</h4>
                    <small>تم التسليم</small>
                </div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">العميل</label>
                        <input type="text" name="customer" class="form-control"
                               placeholder="اسم أو بريد العميل" value="{{ request('customer') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">من تاريخ</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
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
            </form>
        </div>
    </div>

    <!-- جدول الطلبات -->
    <div class="card">
        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>رقم الطلب</th>
                                <th>العميل</th>
                                <th>المنتجات</th>
                                <th>المبلغ</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>
                                        <div>{{ $order->user->name }}</div>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    </td>
                                    <td>{{ $order->orderItems->sum('quantity') }} منتج</td>
                                    <td class="fw-bold">{{ number_format($order->total_price, 2) }} شيقل</td>
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
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.orders.destroy', $order) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
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

                <!-- الترقيم -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5>لا توجد طلبات</h5>
                    <p class="text-muted">لم يتم العثور على طلبات تطابق معايير البحث.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
