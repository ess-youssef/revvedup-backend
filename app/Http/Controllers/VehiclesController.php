<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    public function show(Vehicle $vehicle)
    {
        return $vehicle;
    }

    public function registerVehicle(Request $request)
    {
        $vehicleData = $request->validate([
            'make' => 'required|max:255',
            'model' => 'required|max:255',
            'year' => 'required|numeric',
            'description' => 'required|max:255',
        ]);

        $user = auth()->user();
        $vehicle = $user->vehicles()->create($vehicleData);

        return response()->json($vehicle, 201);
    }

    public function userVehicles(User $user)
    {
        return $user->vehicles;
    }

    public function deleteUserVehicle(Vehicle $vehicle)
    {
        $user = auth()->user();
        if ($user->id != $vehicle->user_id) {
            abort(404, "Vehicle not found");
        }
        $vehicle->delete();
        return ["message" => "Vehicle deleted sucessfully"];
    }

    public function editVehicle(Request $request, Vehicle $vehicle)
    {
        $user = auth()->user();

        if ($user->id != $vehicle->user_id) {
            abort(404, "Vehicle not found");
        }

        $vehicleData = $request->validate([
            'make' => 'required|max:255',
            'model' => 'required|max:255',
            'year' => 'required|numeric',
            'description' => 'required|max:255',
        ]);

        $vehicle->update($vehicleData);

        return $vehicle;
    }
}
