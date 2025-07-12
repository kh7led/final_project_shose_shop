
@extends('layouts.app')

@section('title', 'إدارة الفئات - الإدارة')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tags me-2"></i>إدارة الفئات</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة فئة جديدة
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للوحة التحكم
            </a>
        </div>
    </div>

    <!-- البحث -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.categories.index') }}">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">البحث في الفئات</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="ابحث عن اسم الفئة..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>بحث
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>إزالة الفلتر
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($categories->count() > 0)
                <div class="row g-4">
                    @foreach($categories as $category)
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 category-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title">{{ $category->name }}</h5>
                                        <span class="badge {{ $category->products_count > 0 ? 'bg-primary' : 'bg-secondary' }}">
                                            {{ $category->products_count }} منتج
                                        </span>
                                    </div>

                                    <p class="text-muted small mb-3">
                                        <i class="fas fa-link me-1"></i>{{ $category->slug }}
                                    </p>

                                    <div class="row text-center mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">إجمالي المنتجات</small>
                                            <div class="fw-bold">{{ $category->products_count }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">المتوفر</small>
                                            <div class="fw-bold text-success">
                                                {{ $category->products->where('quantity', '>', 0)->count() }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.categories.show', $category) }}"
                                           class="btn btn-sm btn-outline-primary flex-fill">
                                            <i class="fas fa-eye me-1"></i>عرض
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="btn btn-sm btn-outline-warning flex-fill">
                                            <i class="fas fa-edit me-1"></i>تعديل
                                        </a>
                                        @if($category->products_count == 0)
                                            <form action="{{ route('admin.categories.destroy', $category) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    @if($category->products_count > 0)
                                        <small class="text-muted d-block mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            لا يمكن حذف فئة تحتوي على منتجات
                                        </small>
                                    @endif
                                </div>
                                <div class="card-footer bg-light">
                                    <small class="text-muted">
                                        آخر تحديث: {{ $category->updated_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- الترقيم -->
                @if($categories->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $categories->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <h5>
                        @if(request('search'))
                            لم يتم العثور على فئات
                        @else
                            لا توجد فئات
                        @endif
                    </h5>
                    <p class="text-muted">
                        @if(request('search'))
                            لا توجد فئات تطابق معايير البحث "{{ request('search') }}".
                        @else
                            ابدأ بإضافة فئة جديدة لتنظيم منتجاتك.
                        @endif
                    </p>
                    @if(request('search'))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-times me-2"></i>إزالة البحث
                        </a>
                    @endif
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>إضافة فئة
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.category-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>
@endsection
