<?php

namespace Database\Seeders;

use App\Models\OrderDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderDetials = [
            ['product_customize_id'=>1,'order_id'=>1, 'quantity'=>2, 'price'=> 10],
            ['product_customize_id'=>2,'order_id'=>1,'quantity'=>3, 'price'=> 20],
            ['product_customize_id'=>3,'order_id'=>1,'quantity'=>4, 'price'=> 30],
            ['product_customize_id'=>1,'order_id'=>2, 'quantity'=>10, 'price'=> 100],
            ['product_customize_id'=>2,'order_id'=>2,'quantity'=>3, 'price'=> 20],
            ['product_customize_id'=>3,'order_id'=>2,'quantity'=>5, 'price'=> 30],
        ];
        foreach ($orderDetials as $orderDetial){
            OrderDetail::create($orderDetial);
        }
    }
}
