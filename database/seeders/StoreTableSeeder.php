<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            ['name'=> "Let's Eat",'street'=>"271",'city'=>"Phnom Penh"],
            ['name'=> "Fast Food",'street'=>"371",'city'=>"Phnom Penh"],
            ['name'=> "Eat Eat",'street'=>"321",'city'=>"Siem Reap"],
          
        ];
        foreach ($stores as $store){
            Store::create($store);
        }
    }
}
