@extends('layouts.app')

@section('title', 'إضافة فئة جديدة - الإدارة')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>إضافة فئة جديدة
                    </h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">اسم الفئة *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="مثال: الأحذية الرياضية"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                سيتم إنشاء الرابط (slug) تلقائياً من اسم الفئة
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>إنشاء الفئة
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>معلومات مهمة
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            يجب أن يكون اسم الفئة فريد
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            سيتم إنشاء رابط الفئة تلقائياً
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            يمكن إضافة منتجات للفئة بعد إنشائها
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
