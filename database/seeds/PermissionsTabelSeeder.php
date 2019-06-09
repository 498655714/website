<?php

use Illuminate\Database\Seeder;

class PermissionsTabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 重置角色和权限的缓存
        app()['cache']->forget('spatie.permission.cache');
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        \Spatie\Permission\Models\Role::truncate();
        \Illuminate\Support\Facades\DB::table('model_has_permissions')->truncate();
        \Illuminate\Support\Facades\DB::table('role_has_permissions')->truncate();
        \Spatie\Permission\Models\Permission::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        \Spatie\Permission\Models\Permission::create([
                'name' => 'Administer roles & permissions',
                'chinese_name' => '所有权限',
                'guard_name' => 'admin',
            ]
        );

        // 创建角色并赋予已创建的权限
        $role = \Spatie\Permission\Models\Role::create(['name' => 'super-admin', 'chinese_name' => '超级管理员','guard_name'=>'admin']);
        $role->givePermissionTo(['Administer roles & permissions']);
    }
}
