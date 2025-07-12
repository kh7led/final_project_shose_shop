@extends('layouts.app')

@section('title', 'طلباتي - متجر الأحذية')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">
        <i class="fas fa-receipt me-2"></i>طلباتي
    </h2>

    @if($orders->count() > 0)
        @foreach($orders as $order)
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <strong>طلب رقم #{{ $order->id }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">{{ $order->created_at->format('d M, Y') }}</small>
                        </div>
                        <div class="col-md-3">
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
                        </div>
                        <div class="col-md-3 text-start">
                            <strong class="price-tag">{{ number_format($order->total_price, 2) }} شيقل</strong>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        @foreach($order->orderItems as $item)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="d-flex align-items-center">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             class="rounded me-3" alt="{{ $item->product->name }}"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-shoe-prints text-muted"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                        <small class="text-muted">الكمية: {{ $item->quantity }}</small><br>
                                        <small class="price-tag">{{ number_format($item->price, 2) }}شيقل للقطعة</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                تم الطلب في {{ $order->created_at->format('d F Y \ف\ي g:i A') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-start">
                            <small class="text-muted">
                                الإجمالي: {{ $order->orderItems->sum('quantity') }} منتج
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-5">
            <i class="fas fa-receipt fa-5x text-muted mb-4"></i>
            <h4>لا توجد طلبات بعد</h4>
            <p class="text-muted mb-4">لم تقم بأي طلبات حتى الآن. ابدأ التسوق لترى طلباتك هنا.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i>ابدأ التسوق
            </a>
        </div>
    @endif
</div>
@endsection
