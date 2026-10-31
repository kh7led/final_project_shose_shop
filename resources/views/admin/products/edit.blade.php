@extends('layouts.app')

@section('title', 'تعديل المنتج - ' . $product->name)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>تعديل المنتج: {{ $product->name }}
                    </h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم المنتج *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $product->name) }}"
                                           placeholder="مثال: حذاء رياضي نايك"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">الفئة *</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id"
                                            name="category_id"
                                            required>
                                        <option value="">اختر الفئة</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">وصف المنتج *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="اكتب وصفاً مفصلاً للمنتج..."
                                      required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">السعر (شيقل) *</label>
                                    <input type="number"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price"
                                           name="price"
                                           value="{{ old('price', $product->price) }}"
                                           step="0.01"
                                           min="0"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">كمية المخزون *</label>
                                    <input type="number"
                                           class="form-control @error('quantity') is-invalid @enderror"
                                           id="quantity"
                                           name="quantity"
                                           value="{{ old('quantity', $product->quantity) }}"
                                           min="0"
                                           required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- الصورة الحالية -->
                        @if($product->image)
                            <div class="mb-3">
                                <label class="form-label">الصورة الحالية</label>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="img-thumbnail"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                    <div>
                                        <small class="text-muted">الصورة الحالية للمنتج</small>
                                        <br>
                                        <small class="text-info">اختر صورة جديدة لاستبدالها (اختياري)</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- رفع صورة جديدة -->
                        <div class="mb-4">
                            <label for="image" class="form-label">
                                @if($product->image)
                                    صورة جديدة (اختياري)
                                @else
                                    صورة المنتج
                                @endif
                            </label>
                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="image"
                                   name="image"
                                   accept="image/*">
                            <small class="form-text text-muted">
                                ارفع صورة المنتج (JPEG, PNG, JPG, GIF, WEBP - الحد الأقصى: 2MB)
                            </small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @include('admin.products.partials.video-input', ['product' => $product])

                        <!-- معلومات إضافية -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">معلومات إضافية</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>الرابط الحالي:</strong>
                                            <code>{{ $product->slug }}</code>
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>تاريخ الإنشاء:</strong>
                                            {{ $product->created_at->format('Y-m-d H:i') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>إجمالي المبيعات:</strong>
                                            {{ $product->total_sold ?? 0 }} قطعة
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>آخر تحديث:</strong>
                                            {{ $product->updated_at->format('Y-m-d H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الإجراءات -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>تحديث المنتج
                            </button>
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>عرض المنتج
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- إجراءات خطيرة -->
            <div class="card mt-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>المنطقة الخطيرة
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        حذف هذا المنتج سيؤثر على جميع الطلبات المرتبطة به.
                        هذا الإجراء لا يمكن التراجع عنه.
                    </p>
                    <form action="{{ route('admin.products.destroy', $product) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟ هذا الإجراء لا يمكن التراجع عنه!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>حذف المنتج نهائياً
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview للصورة الجديدة -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                // إنشاء preview للصورة الجديدة
                const reader = new FileReader();
                reader.onload = function(e) {
                    // إزالة preview سابق
                    const existingPreview = document.querySelector('.image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }

                    // إنشاء preview جديد
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'image-preview mt-2';
                    previewDiv.innerHTML = `
                        <div class="d-flex align-items-center gap-3">
                            <img src="${e.target.result}"
                                 alt="معاينة الصورة الجديدة"
                                 class="img-thumbnail"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                            <div>
                                <small class="text-success">
                                    <i class="fas fa-check me-1"></i>تم تحديد صورة جديدة
                                </small>
                                <br>
                                <small class="text-muted">
                                    الحجم: ${(file.size / 1024 / 1024).toFixed(2)} MB
                                </small>
                            </div>
                        </div>
                    `;

                    imageInput.parentNode.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection
