<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromoCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $event = Event::factory()->create();
        return [
            "event_id" => $event->id,
            "amount" => "5000",
            "radius" => "1",
            "radius_unit" => "km",
            "expires_at" => Carbon::now()->toDateTimeString(),
        ];
    }
}
