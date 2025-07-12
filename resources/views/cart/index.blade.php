@extends('layouts.app')

@section('title', 'سلة التسوق - متجر الأحذية')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">
        <i class="fas fa-shopping-cart me-2"></i>سلة التسوق
    </h2>

    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="row align-items-center py-3 border-bottom">
                                <div class="col-md-2">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             class="img-fluid rounded" alt="{{ $item->product->name }}"
                                             style="height: 80px; width: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="height: 80px; width: 80px;">
                                            <i class="fas fa-shoe-prints text-muted"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <small class="text-muted">{{ $item->product->category->name }}</small>
                                    <br>
                                    <small class="price-tag">{{ number_format($item->product->price, 2) }} ر.س</small>
                                </div>

                                <div class="col-md-3">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" name="quantity" class="form-control text-center quantity-input"
                                                   value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary mt-1">تحديث</button>
                                    </form>
                                </div>

                                <div class="col-md-2">
                                    <strong>{{ number_format($item->product->price * $item->quantity, 2) }} ر.س</strong>
                                </div>

                                <div class="col-md-1">
                                    <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('حذف هذا المنتج من السلة؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">ملخص الطلب</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>المجموع الفرعي ({{ $cartItems->sum('quantity') }} منتج):</span>
                            <strong>{{ number_format($total, 2) }} ر.س</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>الشحن:</span>
                            <span>
                                @if($total >= 300)
                                    <span class="text-success">مجاني</span>
                                @else
                                    30.00 ر.س
                                @endif
                            </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>الإجمالي:</strong>
                            <strong class="price-tag">
                                {{ number_format($total >= 300 ? $total : $total + 30, 2) }} ر.س
                            </strong>
                        </div>

                        @if($total < 300)
                            <div class="alert alert-info small">
                                <i class="fas fa-truck me-1"></i>
                                أضف {{ number_format(300 - $total, 2) }} ر.س أخرى للحصول على شحن مجاني!
                            </div>
                        @endif

                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-credit-card me-2"></i>إتمام الشراء
                            </button>
                        </form>

                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-arrow-right me-2"></i>متابعة التسوق
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
            <h4>سلة التسوق فارغة</h4>
            <p class="text-muted mb-4">يبدو أنك لم تضف أي منتجات إلى سلة التسوق بعد.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i>ابدأ التسوق
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityBtns = document.querySelectorAll('.quantity-btn');

    quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            const input = this.parentNode.querySelector('.quantity-input');
            let value = parseInt(input.value);
            const max = parseInt(input.max);

            if (action === 'increase' && value < max) {
                input.value = value + 1;
            } else if (action === 'decrease' && value > 1) {
                input.value = value - 1;
            }
        });
    });
});
</script>
@endpush
@endsection
