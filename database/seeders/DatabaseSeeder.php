<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🇵🇸 بدء تعبئة قاعدة البيانات ببيانات متجر أحذية غزة...');

        // ترتيب تشغيل الـ Seeders مهم لضمان وجود الفئات قبل المنتجات
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
        ]);

        $this->command->info('✅ تم إنجاز تعبئة قاعدة البيانات بنجاح!');
        $this->command->info('');
        $this->command->info('📊 ملخص البيانات المُضافة:');
        $this->command->info('👥 المستخدمين: 2 مدراء + 20 عميل');
        $this->command->info('🏷️ الفئات: 8 فئات متنوعة');
        $this->command->info('👟 المنتجات: 22 منتج متنوع');
        $this->command->info('');
        $this->command->info('🔑 بيانات تسجيل الدخول للمدراء:');
        $this->command->info('📧 admin@gaza-shoes.ps | 🔒 password');
        $this->command->info('📧 fatma@gaza-shoes.ps | 🔒 password');
        $this->command->info('');
        $this->command->info('💡 جميع العملاء لديهم كلمة مرور: password123');
        $this->command->info('🌍 جاهز للخدمة في قطاع غزة الحبيب! 🇵🇸');
    }
}
