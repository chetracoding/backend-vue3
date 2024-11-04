<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'store_id'=>1,
                'role_id'=>2,
                'first_name'=>"Waiter",
                'last_name'=>"Example",
                'gender'=>"Female",
                'email'=>'waiter@example.com',
                'image'=>"https://i.pinimg.com/originals/76/1e/25/761e2551d2c973aae0ddf9043ff5d8ca.jpg",
                'password'=>'123',
            ],
            [
                'store_id'=>1,
                'role_id'=>3,
                'first_name'=>"Chef",
                'last_name'=>"Example",
                'gender'=>"Female",
                'email'=>'chef@example.com',
                'image'=>"https://i.pinimg.com/originals/76/1e/25/761e2551d2c973aae0ddf9043ff5d8ca.jpg",
                'password'=>'123',
            ],
            [
                'store_id'=>1,
                'role_id'=>4,
                'first_name'=>"Cashier",
                'last_name'=>"Example",
                'gender'=>"Female",
                'email'=>'cashier@example.com',
                'image'=>"https://i.pinimg.com/originals/76/1e/25/761e2551d2c973aae0ddf9043ff5d8ca.jpg",
                'password'=>'123',
            ],
            [
                'store_id'=>1,
                'role_id'=>5,
                'first_name'=>"Restaurant Owner",
                'last_name'=>"Example",
                'gender'=>"Male",
                'email'=>'admin@example.com',
                'image'=>"https://avatars.githubusercontent.com/u/123075709?v=4",
                'password'=>'123',
            ],
            [
                'store_id'=>2,
                'role_id'=>5,
                'first_name'=>"Dara",
                'last_name'=>"Sok",
                'gender'=>"Male",
                'email'=>'data@gmail.com',
                'image'=>"https://i.pinimg.com/originals/76/1e/25/761e2551d2c973aae0ddf9043ff5d8ca.jpg",
                'password'=>'123',
            ],
            [
                'store_id'=>3,
                'role_id'=>5,
                'first_name'=>"Lala",
                'last_name'=>"Ly",
                'gender'=>"Female",
                'email'=>'lala@gmail.com',
                'image'=>"https://i.pinimg.com/originals/76/1e/25/761e2551d2c973aae0ddf9043ff5d8ca.jpg",
                'password'=>'123',
            ],
        ];
        foreach ($users as $user){
            User::create($user);
        }
    }
}
