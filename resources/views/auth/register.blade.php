<x-guest-layout>
    <div class="mb-4">
        <h4 class="text-center">إنشاء حساب جديد</h4>
        <p class="text-center text-muted">أدخل بياناتك لإنشاء حساب</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">الاسم الكامل</label>
            <input id="name" class="form-control @error('name') is-invalid @enderror"
                   type="text" name="name" value="{{ old('name') }}" required autofocus />
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror"
                   type="email" name="email" value="{{ old('email') }}" required />
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

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
            <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                   type="password" name="password_confirmation" required />
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>إنشاء الحساب
            </button>
        </div>

        <div class="text-center mt-3">
            <span class="text-muted">لديك حساب بالفعل؟</span>
            <a href="{{ route('login') }}" class="text-decoration-none">تسجيل الدخول</a>
        </div>
    </form>
</x-guest-layout>
