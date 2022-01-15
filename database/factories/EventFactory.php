<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            "name" => "Kira Health Center III",
            "latitude" => "0.4004731",
            "longitude" => "32.6413863",
            "starts_at" => Carbon::now()->toDateTimeString(),
            "ends_at" => Carbon::now()->addHours(12)->toDateTimeString()
        ];
    }
}
