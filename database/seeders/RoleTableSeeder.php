<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name'=> "admin"], // Not using that role yet!
            ['name'=> "waiter"],
            ['name'=> "chef"],
            ['name'=> "cashier"],
            ['name'=> "restaurant_owner"]
        ];
        foreach ($roles as $role){
            Role::create($role);
        }
    }
}
