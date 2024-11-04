<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            ['store_id'=>1,'table_id'=>1, 'datetime'=>"2023-7-10 9:00:00", 'is_completed'=> false, 'is_paid'=> false],
            ['store_id'=>1,'table_id'=>2,'datetime'=>"2023-7-10 10:00:00", 'is_completed'=> false, 'is_paid'=> false]];
        foreach ($orders as $order){
            Order::create($order);
        }
    }
}
