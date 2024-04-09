<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleImage>
 */
class VehicleImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vehicleIds = Vehicle::pluck('id');
        return [
            'image_path'=> "",
            'vehicle_id'=> fake()->randomElement($vehicleIds)
        ];
    }
}
