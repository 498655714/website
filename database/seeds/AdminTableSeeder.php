<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('admins')->truncate();

        //用户
        $user = \App\Models\Admin::create([
            'username' => 'superman',
            'name' => '超级管理员',
            'phone'=>15561371122,
            'avatar'=>'',
            'sex'=>'男',
            'email' => '498655714@qq.com',
            'password' => \Illuminate\Support\Facades\Hash::make("superman"),
            'uuid' => \Faker\Provider\Uuid::uuid(),
            'remarks' => '具有超级管理员,拥有所有权限',
        ]);

        $user->assignRole('super-admin');
    }
}
