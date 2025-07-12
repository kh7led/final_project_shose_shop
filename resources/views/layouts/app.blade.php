<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'متجر الأحذية'))</title>

    <!-- Bootstrap CSS RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #059669;
            --danger-color: #dc2626;
            --warning-color: #d97706;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .alert-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
        }

        .product-card {
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-shoe-prints me-2"></i>متجر الأحذية
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">المنتجات</a>
                    </li>
                </ul>

                <!-- بحث سريع -->
                <form class="d-flex me-3" method="GET" action="{{ route('products.index') }}">
                    <input class="form-control me-2" type="search" name="search" placeholder="ابحث..." value="{{ request('search') }}">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart"></i>
                                السلة
                                <span class="badge bg-danger cart-count" id="cartBadge">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.index') }}">طلباتي</a>
                        </li>
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">الإدارة</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">تسجيل الخروج</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">تسجيل الدخول</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">إنشاء حساب</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-3">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>متجر الأحذية</h5>
                    <p>أفضل تشكيلة من الأحذية العصرية والمريحة</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; {{ date('Y') }} جميع الحقوق محفوظة</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Simple Cart System -->
    <script>
        // إعداد CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ===== عداد السلة =====
        function updateCartCount() {
            fetch('/api/cart/count', {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('cartBadge').textContent = data.count || 0;
            })
            .catch(error => console.log('خطأ في تحديث عداد السلة:', error));
        }

        // ===== إضافة للسلة =====
        function addToCart(productId, quantity = 1) {
            const button = document.querySelector(`[data-product-id="${productId}"]`);

            if (!button) {
                console.error('زر المنتج غير موجود');
                return;
            }

            // حفظ النص الأصلي
            const originalText = button.innerHTML;
            const originalDisabled = button.disabled;

            // إظهار حالة التحميل
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';

            // إرسال الطلب
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quantity: quantity
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // نجح
                    showAlert(data.message, 'success');
                    updateCartCount();

                    // تأثير النجاح
                    button.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة!';
                    button.classList.add('btn-success');

                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = originalDisabled;
                        button.classList.remove('btn-success');
                    }, 2000);
                } else {
                    throw new Error(data.message || 'فشل في الإضافة');
                }
            })
            .catch(error => {
                // فشل
                let errorMessage = 'حدث خطأ أثناء إضافة المنتج';

                if (error.message.includes('401')) {
                    errorMessage = 'يجب تسجيل الدخول أولاً';
                } else if (error.message.includes('404')) {
                    errorMessage = 'المنتج غير موجود';
                } else if (error.message.includes('422')) {
                    errorMessage = 'بيانات غير صحيحة';
                }

                showAlert(errorMessage, 'error');

                // إعادة الزر لحالته الأصلية
                button.innerHTML = originalText;
                button.disabled = originalDisabled;
            });
        }

        // ===== إظهار الرسائل =====
        function showAlert(message, type = 'info') {
            // إزالة الرسائل السابقة
            const existingAlerts = document.querySelectorAll('.alert-floating');
            existingAlerts.forEach(alert => alert.remove());

            // إنشاء رسالة جديدة
            const alertTypes = {
                'success': 'alert-success',
                'error': 'alert-danger',
                'warning': 'alert-warning',
                'info': 'alert-info'
            };

            const alertClass = alertTypes[type] || 'alert-info';

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${alertClass} alert-dismissible fade show alert-floating`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;

            document.body.appendChild(alertDiv);

            // إزالة تلقائية بعد 5 ثوان
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // ===== ربط الأزرار =====
        document.addEventListener('DOMContentLoaded', function() {
            // تحديث عداد السلة عند تحميل الصفحة
            updateCartCount();

            // ربط أزرار إضافة للسلة
            document.addEventListener('click', function(e) {
                const button = e.target.closest('.add-to-cart-btn, [data-action="add-to-cart"]');

                if (button) {
                    e.preventDefault();

                    const productId = button.getAttribute('data-product-id');
                    const quantity = parseInt(button.getAttribute('data-quantity')) || 1;

                    if (productId) {
                        addToCart(productId, quantity);
                    } else {
                        showAlert('معرف المنتج غير موجود', 'error');
                    }
                }
            });

            // ربط حقول الكمية
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('quantity-input')) {
                    const productCard = e.target.closest('.product-card');
                    const addButton = productCard?.querySelector('.add-to-cart-btn');

                    if (addButton) {
                        addButton.setAttribute('data-quantity', e.target.value);
                    }
                }
            });
        });

        // تحديث دوري لعداد السلة (كل 5 دقائق)
        setInterval(updateCartCount, 300000);
    </script>

    @stack('scripts')
</body>
</html>
