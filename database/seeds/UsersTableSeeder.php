<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('users')->truncate();

        //用户
        $user = \App\Models\User::create([
            'username' => 'superman',
            'name' => '超级管理员',
            'phone'=>15561371122,
            'email' => '498655714@qq.com',
            'password' => bcrypt('superman'),
            'uuid' => \Faker\Provider\Uuid::uuid()
        ]);
        
    }
}
