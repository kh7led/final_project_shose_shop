<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'أحذية رجالية',
                'slug' => 'mens-shoes',
                'description' => 'تشكيلة واسعة من الأحذية الرجالية العصرية والكلاسيكية'
            ],
            [
                'name' => 'أحذية نسائية',
                'slug' => 'womens-shoes',
                'description' => 'أحذية نسائية أنيقة ومريحة لجميع المناسبات'
            ],
            [
                'name' => 'أحذية أطفال',
                'slug' => 'kids-shoes',
                'description' => 'أحذية آمنة ومريحة للأطفال من جميع الأعمار'
            ],
            [
                'name' => 'أحذية رياضية',
                'slug' => 'sports-shoes',
                'description' => 'أحذية رياضية متخصصة للجري وكرة القدم والأنشطة المختلفة'
            ],
            [
                'name' => 'صنادل وشباشب',
                'slug' => 'sandals-slippers',
                'description' => 'صنادل وشباشب مريحة للاستخدام اليومي والصيف'
            ],
            [
                'name' => 'أحذية طبية',
                'slug' => 'medical-shoes',
                'description' => 'أحذية طبية متخصصة لراحة القدمين والدعم الطبي'
            ],
            [
                'name' => 'أحذية العمل',
                'slug' => 'work-shoes',
                'description' => 'أحذية آمنة ومتينة للعمل والمهن المختلفة'
            ],
            [
                'name' => 'أحذية المناسبات',
                'slug' => 'formal-shoes',
                'description' => 'أحذية أنيقة للمناسبات الخاصة والرسمية'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
