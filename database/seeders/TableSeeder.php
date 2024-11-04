<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            ['store_id'=>"1",'table_number'=> "A01"],
            ['store_id'=>"1",'table_number'=> "A02"],
            ['store_id'=>"1",'table_number'=> "A03"],
            ['store_id'=>"1",'table_number'=> "A04"],
            ['store_id'=>"1",'table_number'=> "A05"],
        ];
        foreach ($tables as $table){
            Table::create($table);
        }
    }
}
