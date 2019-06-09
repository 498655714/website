<?php

use Illuminate\Database\Seeder;

class WebsiteSetupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('website_setups')->truncate();

        $data =[
            [
                'name' => 'site_name',
                'value' => '通用网站',
                'describe' => '网站名称'
            ],
            [
                'name' => 'domain',
                'value' => 'http://www.website.test',
                'describe' => '网站域名'
            ],
            [
                'name' => 'cache',
                'value' => '0',
                'describe' => '缓存时间'
            ],
            [
                'name' => 'max_upload',
                'value' => '2048',
                'describe' => '最大文件上传'
            ],
            [
                'name' => 'ext_upload',
                'value' => 'png|gif|jpg|jpeg|zip|rar',
                'describe' => '上传文件类型'
            ],
            [
                'name' => 'title',
                'value' => '基于laravel5.5/layuiAdmin2.4通用后台管理模板系统',
                'describe' => '首页标题'
            ],
            [
                'name' => 'keywords',
                'value' => '',
                'describe' => '上传文件类型'
            ],
            [
                'name' => 'description',
                'value' => 'layuiAdmin 是 layui 官方出品的通用型后台模板解决方案，提供了单页版和 iframe 版两种开发模式。layuiAdmin 是目前非常流行的后台模板框架，广泛用于各类管理平台。',
                'describe' => 'META描述'
            ],
            [
                'name' => 'copyright',
                'value' => '© 2018 layui.com MIT license',
                'describe' => '版权信息'
            ],
        ];

        foreach($data as $item){
            \App\Models\WebsiteSetup::create($item);
        }
    }
}
