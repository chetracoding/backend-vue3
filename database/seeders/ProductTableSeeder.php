<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'store_id'=> 1,
                'category_id' => 1,
                'name'=>"Pizza",
                'product_code'=>"PZ123",
                'description'=>"Seafood Pizza",
                'image'=>"https://www.bacinos.com/wp-content/uploads/2021/05/30-Seafood-Pizza-Recipes-500x375.jpg",
                'is_active'=>true
            ],
            [
                'store_id'=> 1,
                'category_id' => 1,
                'name'=>"Hamburger",
                'product_code'=>"HG123",
                'description'=>"Seafood Hamburger",
                'image'=>"https://www.kamaboko.com/en/wp-content/uploads/surimi_15_008-1024x683.jpeg",
                'is_active'=>true
            ],
        ];
        foreach ($products as $product){
            Product::create($product);
        }
    }
}
