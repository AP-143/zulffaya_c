<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        Product::create(['name' => 'Product 1', 'price' => 100.00, 'img' => 'path/to/image1.jpg']);
        Product::create(['name' => 'Product 2', 'price' => 200.00, 'img' => 'path/to/image2.jpg']);
    }
}
