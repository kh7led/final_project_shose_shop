@extends('layouts.app')

@section('title', 'تعديل المستخدم - ' . $user->name)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>تعديل المستخدم: {{ $user->name }}
                    </h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني *</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">الدور *</label>
                            <select class="form-select @error('role') is-invalid @enderror"
                                    id="role"
                                    name="role"
                                    required>
                                <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>
                                    عميل
                                </option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                    مدير
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="اتركها فارغة للاحتفاظ بكلمة المرور الحالية">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                أدخل كلمة مرور جديدة فقط إذا كنت تريد تغييرها
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="أعد إدخال كلمة المرور الجديدة">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>تحديث المستخدم
                            </button>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>عرض التفاصيل
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>العودة للمستخدمين
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>معلومات المستخدم
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">تاريخ التسجيل:</small><br>
                            <strong>{{ $user->created_at->format('Y-m-d H:i') }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">آخر تحديث:</small><br>
                            <strong>{{ $user->updated_at->format('Y-m-d H:i') }}</strong>
                        </div>
                        <div class="col-6 mt-3">
                            <small class="text-muted">عدد الطلبات:</small><br>
                            <strong>{{ $user->orders->count() }} طلب</strong>
                        </div>
                        <div class="col-6 mt-3">
                            <small class="text-muted">منتجات في السلة:</small><br>
                            <strong>{{ $user->carts->count() }} منتج</strong>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->id !== auth()->id())
                <!-- خطر: حذف المستخدم -->
                <div class="card mt-4 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>منطقة خطر
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">حذف هذا المستخدم سيؤدي إلى فقدان جميع بياناته نهائياً.</p>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟ هذا الإجراء لا يمكن التراجع عنه!')">
                                <i class="fas fa-trash me-2"></i>حذف المستخدم
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
