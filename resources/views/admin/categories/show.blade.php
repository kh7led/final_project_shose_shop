@extends('layouts.app')

@section('title', 'تفاصيل الفئة - ' . $category->name)

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-tag me-2"></i>{{ $category->name }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>تعديل
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للفئات
            </a>
        </div>
    </div>

    <div class="row">
        <!-- معلومات الفئة -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">معلومات الفئة</h5>
                </div>
                <div class="card-body">
                    <p><strong>الاسم:</strong> {{ $category->name }}</p>
                    <p><strong>الرابط:</strong> <code>{{ $category->slug }}</code></p>
                    <p><strong>عدد المنتجات:</strong> {{ $category->products->count() }} منتج</p>
                    <p><strong>تاريخ الإنشاء:</strong> {{ $category->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>آخر تحديث:</strong> {{ $category->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- منتجات الفئة -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">منتجات الفئة</h5>
                    <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>إضافة منتج
                    </a>
                </div>
                <div class="card-body">
                    @if($category->products->count() > 0)
                        <div class="row g-3">
                            @foreach($category->products as $product)
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                         class="rounded me-3"
                                                         style="width: 50px; height: 50px; object-fit: cover;"
                                                         alt="{{ $product->name }}">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-shoe-prints text-muted"></i>
                                                    </div>
                                                @endif

                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                                    <small class="text-muted">{{ number_format($product->price, 2) }} شيقل</small>
                                                </div>
                                            </div>

                                            <div class="d-flex gap-1">
                                                <a href="{{ route('products.show', $product->slug) }}"
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                   class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5>لا توجد منتجات في هذه الفئة</h5>
                            <p class="text-muted">ابدأ بإضافة منتجات لهذه الفئة</p>
                            <a href="{{ route('admin.products.create') }}?category={{ $category->id }}"
                               class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>إضافة منتج
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
