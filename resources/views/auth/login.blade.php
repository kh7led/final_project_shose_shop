<x-guest-layout>
    <div class="mb-4">
        <h4 class="text-center">تسجيل الدخول</h4>
        <p class="text-center text-muted">ادخل بياناتك للوصول لحسابك</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror"
                   type="email" name="email" value="{{ old('email') }}" required autofocus />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror"
                   type="password" name="password" required />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">تذكرني</label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
                <a class="text-decoration-none" href="{{ route('password.request') }}">
                    نسيت كلمة المرور؟
                </a>
            @endif

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
            </button>
        </div>

        <div class="text-center mt-3">
            <span class="text-muted">ليس لديك حساب؟</span>
            <a href="{{ route('register') }}" class="text-decoration-none">إنشاء حساب جديد</a>
        </div>
    </form>
</x-guest-layout>
