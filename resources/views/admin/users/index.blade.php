@extends('layouts.app')

@section('title', 'إدارة المستخدمين - الإدارة')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users me-2"></i>إدارة المستخدمين</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة مستخدم جديد
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للوحة التحكم
            </a>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-primary">{{ $stats['total'] }}</h4>
                    <small>إجمالي المستخدمين</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-warning">{{ $stats['admins'] }}</h4>
                    <small>المدراء</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-success">{{ $stats['customers'] }}</h4>
                    <small>العملاء</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="text-info">{{ $stats['recent'] }}</h4>
                    <small>جدد هذا الأسبوع</small>
                </div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">الدور</label>
                        <select name="role" class="form-select">
                            <option value="">جميع الأدوار</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مدير</option>
                            <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>عميل</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">البحث</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="اسم أو بريد إلكتروني" value="{{ request('search') }}">
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

    <!-- جدول المستخدمين -->
    <div class="card">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>المستخدم</th>
                                <th>الدور</th>
                                <th>الطلبات</th>
                                <th>السلة</th>
                                <th>تاريخ التسجيل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->id === auth()->id())
                                                <span class="badge bg-info ms-1">أنت</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $user->role == 'admin' ? 'bg-warning' : 'bg-success' }}">
                                            {{ $user->role == 'admin' ? 'مدير' : 'عميل' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->orders_count }} طلب</td>
                                    <td>{{ $user->carts_count }} منتج</td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5>لا توجد مستخدمين</h5>
                    <p class="text-muted">لم يتم العثور على مستخدمين يطابقون معايير البحث.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
