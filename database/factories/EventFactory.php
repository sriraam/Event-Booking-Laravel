<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'       => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'starts_at'   => Carbon::now()->addDays(rand(1, 10)),
            'location'    => $this->faker->city,
            'capacity'    => $this->faker->numberBetween(10, 100),
            'creator_id'  => User::factory(),  // links to an organiser by default
        ];
    }
}
