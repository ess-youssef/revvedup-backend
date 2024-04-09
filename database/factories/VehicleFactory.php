<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id');
        return [
            'make' => fake()->sentence(),
            'model' => fake()->sentence(),
            'year' => fake()->year(),
            'description' => fake()->paragraph(6),
            'user_id'=> fake()->randomElement($userIds)
        ];  
    }
}
