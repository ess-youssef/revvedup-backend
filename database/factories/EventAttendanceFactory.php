<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventAttendance>
 */
class EventAttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id');
        $eventIds = Event::pluck('id');
        return [
            'user_id' => fake()->randomElement($userIds),
            'event_id' => fake()->randomElement($eventIds),
        ];
    }
}
