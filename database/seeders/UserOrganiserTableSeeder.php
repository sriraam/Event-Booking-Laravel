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
            'name'=>'test',
            'email'=>'test@test.com',
            'password'=>bcrypt('password'),
            'role'=>'attendee'
        ]);
        DB::table('users')->insert([
            'name'=>'sriraam',
            'email'=>'sriraam@cbh.com',
            'password'=>bcrypt('password'),
            'role'=>'attendee'
        ]);
        DB::table('users')->insert([
            'name'=>'Vaishnavi',
            'email'=>'vaishnavi@cbh.com',
            'password'=>bcrypt('password'),
            'role'=>'attendee'
        ]);
        DB::table('users')->insert([
            'name'=>'Mark',
            'email'=>'mark@cbh.com',
            'password'=>bcrypt('password'),
            'role'=>'attendee'
        ]);
        DB::table('users')->insert([
            'name'=>'Robert',
            'email'=>'robert@cbh.com',
            'password'=>bcrypt('password'),
            'role'=>'attendee'
        ]);

    }
}
