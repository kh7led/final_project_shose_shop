```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};



### 11. seeders\CategorySeeder.php

*نوع:* php | *الحجم:* 491 بايت

php
<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Sports Shoes'],
            ['name' => 'Casual Shoes'],
            ['name' => 'Formal Shoes'],
            ['name' => 'Boots'],
            ['name' => 'Sandals'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

```

### 12. seeders\DatabaseSeeder.php

*نوع:* php | *الحجم:* 475 بايت

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            UserSeeder::class,

        ]);
    }
}


```
### 13. seeders\ProductSeeder.php

*نوع:* php | *الحجم:* 1,297 بايت

```php
<?php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'Nike Air Max 270',
                'description' => 'Comfortable running shoes with air cushioning',
                'price' => 150.00,
                'quantity' => 50,
            ],
            [
                'category_id' => 1,
                'name' => 'Adidas Ultraboost 22',
                'description' => 'High-performance running shoes',
                'price' => 180.00,
                'quantity' => 30,
            ],
            [
                'category_id' => 2,
                'name' => 'Converse Chuck Taylor',
                'description' => 'Classic casual sneakers',
                'price' => 80.00,
                'quantity' => 40,
            ],
            [
                'category_id' => 3,
                'name' => 'Oxford Leather Shoes',
                'description' => 'Professional formal shoes',
                'price' => 120.00,
                'quantity' => 25,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}


```
### 14. seeders\UserSeeder.php

*نوع:* php | *الحجم:* 655 بايت

```php
<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@shoestore.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create customer user
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}

```