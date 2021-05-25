<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Ivan',
            'email' => Str::random(10).'@gmail.com',
            'course' => 2,
            'group' => 8,
            'average_score' => 0,
            'status' => 'Student',
            'password' => Hash::make('123'),
        ]);
    }
}
