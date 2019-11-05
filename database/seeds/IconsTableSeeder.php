<?php

use Illuminate\Database\Seeder;

class IconsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Icon::truncate();
        $file = file_get_contents(public_path().'/icons.json');
        $icons = json_decode($file,true);
        foreach ($icons as $icon){
            \App\Models\Icon::create($icon);
        }
    }
}
