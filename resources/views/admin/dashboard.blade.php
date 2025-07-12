@extends('layouts.app')

@section('title', 'لوحة التحكم - الإدارة')

@section('content')
<div class="container py-5">
    <!-- رأس لوحة التحكم -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم</h2>
            <p class="text-muted mb-0">مرحباً {{ auth()->user()->name }}، إليك نظرة عامة على متجرك</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="refreshDashboard()" class="btn btn-outline-primary">
                <i class="fas fa-sync-alt me-2"></i>تحديث
            </button>
            <button onclick="exportReport()" class="btn btn-success">
                <i class="fas fa-download me-2"></i>تصدير تقرير
            </button>
        </div>
    </div>

    <!-- الإحصائيات السريعة -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="fw-bold">{{ number_format($basicStats['total_products']) }}</h4>
                            <p class="mb-0">إجمالي المنتجات</p>
                            <small class="opacity-75">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $basicStats['low_stock_products'] }} منتج قليل المخزون
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shoe-prints fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-25">
                    <a href="{{ route('admin.products.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>عرض المنتجات
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="fw-bold">{{ number_format($basicStats['total_orders']) }}</h4>
                            <p class="mb-0">إجمالي الطلبات</p>
                            <small class="opacity-75">
                                <i class="fas fa-clock me-1"></i>
                                {{ $basicStats['pending_orders'] }} طلب معلق
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-25">
                    <a href="{{ route('admin.orders.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>إدارة الطلبات
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="fw-bold">{{ number_format($basicStats['total_customers']) }}</h4>
                            <p class="mb-0">العملاء</p>
                            <small class="opacity-75">
                                <i class="fas fa-user-plus me-1"></i>
                                @if(isset($newUsersActivity))
                                    {{ $newUsersActivity['today_new_users'] }} جديد اليوم
                                @else
                                    0 جديد اليوم
                                @endif
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-25">
                    <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>إدارة المستخدمين
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="fw-bold">{{ number_format($basicStats['total_revenue'], 0) }}</h4>
                            <p class="mb-0">الإيرادات (شيكل)</p>
                            <small class="opacity-75">
                                <i class="fas fa-chart-line me-1"></i>
                                @if(isset($orderStats))
                                    {{ number_format($orderStats['month_revenue'], 0) }}شيكل  هذا الشهر
                                @else
                                    0 شيكل هذا الشهر
                                @endif
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-25">
                    <span class="text-white">
                        <i class="fas fa-info-circle me-1"></i>إجمالي المبيعات
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات الطلبات التفصيلية -->
    @if(isset($orderStats))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>إحصائيات الطلبات</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-6 text-center">
                            <div class="stat-item">
                                <div class="stat-number text-warning">{{ $orderStats['pending'] }}</div>
                                <div class="stat-label">في الانتظار</div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 text-center">
                            <div class="stat-item">
                                <div class="stat-number text-info">{{ $orderStats['processing'] }}</div>
                                <div class="stat-label">قيد المعالجة</div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 text-center">
                            <div class="stat-item">
                                <div class="stat-number text-primary">{{ $orderStats['shipped'] }}</div>
                                <div class="stat-label">تم الشحن</div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 text-center">
                            <div class="stat-item">
                                <div class="stat-number text-success">{{ $orderStats['delivered'] }}</div>
                                <div class="stat-label">تم التسليم</div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 text-center">
                            <div class="stat-item">
                                <div class="stat-number text-primary">{{ $orderStats['today_orders'] }}</div>
                                <div class="stat-label">طلبات اليوم</div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 text-center">
                            <div class="stat-item">
                                <div class="stat-number text-success">{{ number_format($orderStats['average_order_value'], 0) }}</div>
                                <div class="stat-label">متوسط قيمة الطلب</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- الطلبات الحديثة -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>الطلبات الحديثة</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                        عرض الكل
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentOrders) && $recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم الطلب</th>
                                        <th>العميل</th>
                                        <th>المبلغ</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                        <th>إجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td><strong>#{{ $order->id }}</strong></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-circle">
                                                            {{ substr($order->user->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $order->user->name }}</div>
                                                        <small class="text-muted">{{ $order->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
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
                                            <td>{{ $order->created_at->diffForHumans() }}</td>
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
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h6>لا توجد طلبات حديثة</h6>
                            <p class="text-muted">ستظهر الطلبات الجديدة هنا</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- الشريط الجانبي -->
        <div class="col-lg-4">
            <!-- منتجات قليلة المخزون -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>تنبيه المخزون</h6>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-warning">
                        عرض الكل
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
                        @foreach($lowStockProducts as $product)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-medium">{{ Str::limit($product->name, 20) }}</h6>
                                    <small class="text-muted">{{ $product->category->name }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $product->quantity == 0 ? 'bg-danger' : 'bg-warning' }}">
                                        {{ $product->quantity }} متبقي
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <p class="text-muted mb-0">جميع المنتجات متوفرة</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- أفضل المنتجات مبيعاً -->
            @if(isset($topSellingProducts) && $topSellingProducts->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-trophy me-2"></i>الأكثر مبيعاً</h6>
                </div>
                <div class="card-body">
                    @foreach($topSellingProducts as $index => $product)
                        <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="rank-badge me-3">
                                <span class="badge
                                    @if($index == 0) bg-warning
                                    @elseif($index == 1) bg-secondary
                                    @elseif($index == 2) bg-dark
                                    @else bg-light text-dark @endif">
                                    {{ $index + 1 }}
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-medium">{{ Str::limit($product->name, 18) }}</h6>
                                <small class="text-muted">{{ $product->category->name }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">{{ $product->total_sold ?? 0 }}</div>
                                <small class="text-muted">مبيع</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- إحصائيات سلة التسوق -->
            @if(isset($cartStats))
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-shopping-basket me-2"></i>إحصائيات السلة</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-info">{{ $cartStats['active_users_with_carts'] }}</div>
                                <div class="stat-label small">سلة نشطة</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-warning">{{ number_format($cartStats['average_cart_items'], 1) }}</div>
                                <div class="stat-label small">متوسط المنتجات</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-danger">{{ $cartStats['abandoned_carts'] }}</div>
                                <div class="stat-label small">سلة متروكة</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-success">{{ $cartStats['cart_conversion_rate'] }}%</div>
                                <div class="stat-label small">معدل التحويل</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- الرسوم البيانية -->
    @if(isset($monthlySales))
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i>المبيعات الشهرية (آخر 6 أشهر)</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlySalesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.stat-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #20c997);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #6f42c1);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
}

.stat-icon {
    opacity: 0.7;
}

.stat-item {
    padding: 15px 10px;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    line-height: 1;
}

.stat-label {
    color: #6c757d;
    font-weight: 500;
}

.avatar-circle {
    width: 35px;
    height: 35px;
    background: linear-gradient(45deg, #007bff, #6610f2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.9rem;
}

.rank-badge .badge {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

@media (max-width: 768px) {
    .stat-number {
        font-size: 1.5rem;
    }

    .stat-card .card-body {
        padding: 1rem;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// رسم بياني للمبيعات الشهرية
@if(isset($monthlySales))
const ctx = document.getElementById('monthlySalesChart').getContext('2d');
const monthlySalesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlySales['months']) !!},
        datasets: [{
            label: 'المبيعات (شيقل)',
            data: {!! json_encode($monthlySales['revenues']) !!},
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + 'شيقل';
                    }
                }
            }
        }
    }
});
@endif

// وظائف الصفحة
function refreshDashboard() {
    location.reload();
}

function exportReport() {
    // يمكن إضافة وظيفة تصدير التقارير
    alert('سيتم إضافة وظيفة تصدير التقارير قريباً');
}

// تحديث البيانات كل 5 دقائق
setInterval(function() {
    // يمكن إضافة Ajax لتحديث الإحصائيات دون إعادة تحميل الصفحة
}, 300000);
</script>
@endsection
