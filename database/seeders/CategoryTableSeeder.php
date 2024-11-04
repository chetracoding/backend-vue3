<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['store_id'=>1,'name'=> "Pizza"],
            ['store_id'=>1,'name'=> "Hamburger"],
            ['store_id'=>1,'name'=> "Drink"],
            ['store_id'=>2,'name'=> "French Fry"],
            ['store_id'=>2,'name'=> "Fry Chicken"],
          
        ];
        foreach ($categories as $category){
            Category::create($category);
        }
    }
}
