# متجر الأحذية - Laravel E-Commerce

متجر إلكتروني متكامل لبيع الأحذية مبني باستخدام Laravel 10 مع واجهة إدارة شاملة.

## 🚀 المميزات

### للعملاء:
- ✅ تصفح المنتجات مع البحث والفلترة
- ✅ سلة تسوق تفاعلية
- ✅ نظام طلبات كامل
- ✅ واجهة مستخدم عصرية ومتجاوبة
- ✅ تسجيل دخول وإنشاء حسابات

### للمديرين:
- ✅ لوحة تحكم شاملة مع إحصائيات مفصلة
- ✅ إدارة المنتجات (إضافة، تعديل، حذف)
- ✅ إدارة الفئات
- ✅ إدارة الطلبات وتحديث حالاتها
- ✅ إدارة المستخدمين
- ✅ البحث والفلترة في جميع الأقسام
- ✅ رسوم بيانية ومؤشرات أداء

## 🛠 التقنيات المستخدمة

- **Backend:** Laravel 10
- **Frontend:** Bootstrap 5 + Font Awesome
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **File Storage:** Laravel Storage (Public Disk)

## 📋 متطلبات التشغيل

- PHP 8.1+
- Composer
- MySQL 5.7+
- Node.js & NPM (للأصول)

## ⚡ التثبيت والإعداد

### 1. استنساخ المشروع
```bash
git clone <repository-url>
cd shoes-store
```

### 2. تثبيت التبعيات
```bash
composer install
npm install && npm run build
```

### 3. إعداد البيئة
```bash
cp .env.example .env
php artisan key:generate
```

### 4. إعداد قاعدة البيانات
قم بتحديث ملف `.env` بإعدادات قاعدة البيانات:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shoes_store
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. تشغيل الميجرشن والسيدرز
```bash
php artisan migrate
php artisan db:seed
```

### 6. ربط المجلد العام للتخزين
```bash
php artisan storage:link
```

### 7. إصلاح slugs المنتجات (إذا لزم الأمر)
```bash
php artisan products:fix-slugs
```

### 8. تشغيل الخادم
```bash
php artisan serve
```

## 👤 بيانات تسجيل الدخول الافتراضية

### مدير النظام:
- **البريد الإلكتروني:** admin@admin.com
- **كلمة المرور:** password

### عميل تجريبي:
- **البريد الإلكتروني:** customer@test.com
- **كلمة المرور:** password

## 📁 هيكل المشروع

```
app/
├── Http/Controllers/
│   ├── Admin/           # كنترولرز الإدارة
│   ├── Auth/            # كنترولرز المصادقة
│   ├── CartController   # إدارة السلة
│   ├── OrderController  # إدارة الطلبات
│   └── ProductController # عرض المنتجات
├── Models/              # نماذج البيانات
└── Console/Commands/    # أوامر مخصصة

resources/views/
├── admin/              # واجهات الإدارة
├── auth/              # واجهات تسجيل الدخول
├── cart/              # واجهات السلة
├── orders/            # واجهات الطلبات
├── products/          # واجهات المنتجات
└── errors/            # صفحات الأخطاء
```

## 🔧 الإعدادات المتقدمة

### إضافة منتجات وفئات جديدة:
1. سجل دخولك كمدير
2. اذهب إلى `/admin`
3. استخدم قسم "إدارة الفئات" لإضافة فئات
4. استخدم قسم "إدارة المنتجات" لإضافة منتجات

### تخصيص الواجهة:
- الملفات الأساسية في `resources/views/layouts/app.blade.php`
- الأنماط المخصصة في كل صفحة
- استخدم Bootstrap 5 للتخصيص

### إدارة الصور:
- الصور تُحفظ في `storage/app/public/products/`
- يجب تشغيل `php artisan storage:link` لربط المجلد العام

## 🛡 الأمان

- حماية CSRF على جميع النماذج
- تحقق من الصلاحيات للوصول لوحة الإدارة
- تشفير كلمات المرور
- تحديد معدل الطلبات (Rate Limiting)

## 🐛 استكشاف الأخطاء

### مشاكل شائعة:

1. **صور المنتجات لا تظهر:**
   ```bash
   php artisan storage:link
   ```

2. **خطأ في الصلاحيات:**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

3. **مشاكل في slugs المنتجات:**
   ```bash
   php artisan products:fix-slugs
   ```

## 📊 المؤشرات والإحصائيات

لوحة التحكم تعرض:
- إجمالي المنتجات والطلبات
- إحصائيات المبيعات الشهرية
- المنتجات الأكثر مبيعاً
- المنتجات قليلة المخزون
- نشاط المستخدمين الجدد

## 🔄 التحديثات المستقبلية

- [ ] نظام دفع إلكتروني
- [ ] تتبع الشحنات
- [ ] نظام تقييم المنتجات
- [ ] نظام كوبونات الخصم
- [ ] API للتطبيقات المحمولة
