<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء حساب المدير الرئيسي
        User::create([
            'name' => 'خالد محمود عبد العاطي',
            'email' => 'khaldbdalaty664@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //  مدير ثانوي
        User::create([
            'name' => 'فاطمة أحمد الغزي',
            'email' => 'fatma@gaza-shoes.ps',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // عملاء من غزة بأسماء فلسطينية
        $customers = [
            [
                'name' => 'محمد عبدالله النجار',
                'email' => 'mohammed.najjar@gmail.com'
            ],
            [
                'name' => 'أسماء يوسف أبو دقة',
                'email' => 'asmaa.abudaqqa@gmail.com'
            ],
            [
                'name' => 'عمر خالد البرغوثي',
                'email' => 'omar.barghouti@gmail.com'
            ],
            [
                'name' => 'ريم سعيد أبو شعبان',
                'email' => 'reem.abushaban@gmail.com'
            ],
            [
                'name' => 'يوسف محمود الكحلوت',
                'email' => 'yousef.kahlout@gmail.com'
            ],
            [
                'name' => 'نور الدين أحمد زقوت',
                'email' => 'noureddine.zaqout@gmail.com'
            ],
            [
                'name' => 'هدى عبدالرحمن أبو جزر',
                'email' => 'huda.abujazer@gmail.com'
            ],
            [
                'name' => 'سليم فايز الهباش',
                'email' => 'saleem.habbash@gmail.com'
            ],
            [
                'name' => 'مريم عصام أبو مطر',
                'email' => 'mariam.abumatar@gmail.com'
            ],
            [
                'name' => 'خليل إبراهيم الشنطي',
                'email' => 'khalil.shanti@gmail.com'
            ],
            [
                'name' => 'لينا صالح أبو العز',
                'email' => 'lina.abualez@gmail.com'
            ],
            [
                'name' => 'طارق منصور الريس',
                'email' => 'tariq.alreis@gmail.com'
            ],
            [
                'name' => 'سارة ماجد أبو شمالة',
                'email' => 'sara.abushamala@gmail.com'
            ],
            [
                'name' => 'كمال عادل البطش',
                'email' => 'kamal.albatsh@gmail.com'
            ],
            [
                'name' => 'دعاء رامي الفرا',
                'email' => 'duaa.alfarra@gmail.com'
            ],
            [
                'name' => 'وليد سمير أبو ركبة',
                'email' => 'waleed.aburakaba@gmail.com'
            ],
            [
                'name' => 'ياسمين كريم صيام',
                'email' => 'yasmeen.siyam@gmail.com'
            ],
            [
                'name' => 'إياد فؤاد الغندور',
                'email' => 'iyad.ghandour@gmail.com'
            ],
            [
                'name' => 'سلمى عماد أبو ناصر',
                'email' => 'salma.abunasser@gmail.com'
            ],
            [
                'name' => 'حسام تيسير المدهون',
                'email' => 'hossam.madhoun@gmail.com'
            ]
        ];

        foreach ($customers as $customer) {
            User::create([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'email_verified_at' => now(),
                'created_at' => now()->subDays(rand(1, 90)),
                'updated_at' => now(),
            ]);
        }
    }
}
