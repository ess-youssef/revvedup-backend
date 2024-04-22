<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vehicleIds = Vehicle::pluck('id');
        $userIds = User::pluck('id');
        return [
            'price' => fake()->randomFloat(2),
            'status' => fake()->randomElement(["SOLD","FORSALE"]),
            'vehicle_id'=> fake()->randomElement($vehicleIds),
            'user_id'=> fake()->randomElement($userIds),
        ];
    }
}
