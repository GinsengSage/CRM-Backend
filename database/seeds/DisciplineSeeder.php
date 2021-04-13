<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisciplineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('disciplines')->insert([
            'name' => 'Math',
            'text' => Str::random(50),
            'image' => Str::random(10).'.jpg',
        ]);
    }
}
