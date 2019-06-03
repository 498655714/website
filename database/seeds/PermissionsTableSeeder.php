<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('permissions')->truncate();

        $permissions = \Spatie\Permission\Models\Permission::create(
            [
                'name' => 'Administer roles & permissions',
                'chinese_name' => '所有权限',
                'guard_name' => 'admin',
            ]
        );
    }
}
