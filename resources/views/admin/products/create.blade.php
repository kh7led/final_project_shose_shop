@extends('layouts.app')

@section('title', 'إضافة منتج جديد - الإدارة')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>إضافة منتج جديد
                    </h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم المنتج *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
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
                                                    {{ old('category_id', request('category')) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($categories->isEmpty())
                                        <small class="form-text text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            لا توجد فئات متاحة. 
                                            <a href="{{ route('admin.categories.create') }}">أضف فئة جديدة</a>
                                        </small>
                                    @endif
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
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الحد الأدنى 10 أحرف</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">السعر (شيقل) *</label>
                                    <input type="number"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price"
                                           name="price"
                                           value="{{ old('price') }}"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00"
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
                                           value="{{ old('quantity') }}"
                                           min="0"
                                           placeholder="0"
                                           required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">صورة المنتج</label>
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

                        @include('admin.products.partials.video-input')

                        <!-- معاينة البيانات -->
                        <div class="card bg-light mb-4" id="productPreview" style="display: none;">
                            <div class="card-header">
                                <h6 class="mb-0">معاينة المنتج</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="imagePreview"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 id="namePreview"></h6>
                                        <p class="text-muted" id="categoryPreview"></p>
                                        <p id="descriptionPreview"></p>
                                        <h5 class="text-primary" id="pricePreview"></h5>
                                        <small class="text-muted" id="quantityPreview"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" {{ $categories->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-save me-2"></i>إنشاء المنتج
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>إلغاء
                            </a>
                        </div>

                        @if($categories->isEmpty())
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>تنبيه:</strong> يجب إضافة فئة واحدة على الأقل قبل إنشاء المنتجات.
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-warning ms-2">
                                    إضافة فئة الآن
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript للمعاينة المباشرة -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const preview = document.getElementById('productPreview');
    const fields = {
        name: document.getElementById('name'),
        category: document.getElementById('category_id'),
        description: document.getElementById('description'),
        price: document.getElementById('price'),
        quantity: document.getElementById('quantity'),
        image: document.getElementById('image')
    };

    // تحديث المعاينة عند تغيير أي حقل
    Object.values(fields).forEach(field => {
        if (field) {
            field.addEventListener('input', updatePreview);
            field.addEventListener('change', updatePreview);
        }
    });

    function updatePreview() {
        const hasData = fields.name.value || fields.description.value || fields.price.value;
        
        if (hasData) {
            preview.style.display = 'block';
            
            // اسم المنتج
            document.getElementById('namePreview').textContent = fields.name.value || 'اسم المنتج';
            
            // الفئة
            const categoryOption = fields.category.options[fields.category.selectedIndex];
            document.getElementById('categoryPreview').textContent = categoryOption ? categoryOption.text : 'الفئة';
            
            // الوصف
            document.getElementById('descriptionPreview').textContent = 
                fields.description.value ? fields.description.value.substring(0, 100) + '...' : 'وصف المنتج';
            
            // السعر
            const price = fields.price.value ? parseFloat(fields.price.value).toFixed(2) : '0.00';
            document.getElementById('pricePreview').textContent = price + ' شيقل';
            
            // الكمية
            const quantity = fields.quantity.value || '0';
            document.getElementById('quantityPreview').textContent = الكمية المتوفرة: ${quantity};
            
        } else {
            preview.style.display = 'none';
        }
    }

    // معاينة الصورة
    fields.image.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const imagePreview = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `
                    <img src="${e.target.result}" 
                         alt="معاينة الصورة" 
                         class="img-thumbnail"
                         style="width: 100%; max-width: 200px; height: auto;">
                `;
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = `
                <div class="bg-light border rounded d-flex align-items-center justify-content-center" 
                     style="width: 100%; height: 150px;">
                    <i class="fas fa-image fa-3x text-muted"></i>
                </div>
            `;
        }
    });

    // تشغيل المعاينة عند تحميل الصفحة للقيم المُحفوظة
    updatePreview();
});
</script>