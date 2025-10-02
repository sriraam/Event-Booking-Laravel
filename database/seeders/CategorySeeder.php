<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Trivia', 'color' => '#3b52f6'],
            ['name' => 'Strategy', 'color' => '#10a34a'],
            ['name' => 'Tournament', 'color' => '#dc2026'],
            ['name' => 'GameNight', 'color' => '#5333ea'],
            ['name' => 'OpenTable', 'color' => '#f50e0b'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
