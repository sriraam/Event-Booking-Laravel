<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'title' => 'Board Game Night - Settlers of Catan',
            'description' => 'Join us for a night of strategy and fun with Settlers of Catan.',
            'starts_at' => Carbon::now()->addDays(15),
            'location' => 'CBH - Southport',
            'capacity' => 10,
            'creator_id' => 1,
        ]);

        Event::create([
            'title' => 'Chess Tournament',
            'description' => 'Test your skills in CoimbatoreVoardgameHouse chess competition.',
            'starts_at' => Carbon::now()->addWeek(),
            'location' => 'CBH - Labrador',
            'capacity' => 20,
            'creator_id' => 2,
        ]);

        Event::create([
            'title' => 'Splendor Challenge',
            'description' => 'Compete to be the jewel of Splendor!',
            'starts_at' => Carbon::now()->addDays(10),
            'location' => 'CBH - Labrador',
            'capacity' => 15,
            'creator_id' => 1,
        ]);
        
    }
}
