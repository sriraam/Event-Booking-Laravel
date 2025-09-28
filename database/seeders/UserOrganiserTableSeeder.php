<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserOrganiserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name'=>'admin',
            'email'=>'admin@test.com',
            'password'=>bcrypt('password'),
            'role'=>'organiser'
        ]);
        DB::table('users')->insert([
            'name'=>'test',
            'email'=>'test@test.com',
            'password'=>bcrypt('password'),
            'role'=>'attendee'
        ]);
    }
}
