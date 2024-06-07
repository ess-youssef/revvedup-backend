<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VehiclesController extends Controller
{
    public function show(Vehicle $vehicle)
    {
        $vehicle->load("images");
        $vehicle->load("owner");
        return $vehicle;
    }

    public function registerVehicle(Request $request)
    {
        $vehicleData = $request->validate([
            'make' => 'required|max:255',
            'model' => 'required|max:255',
            'year' => 'required|numeric',
            'description' => 'required|max:255',
            'photos.*' => 'required|mimes:jpg,png,jpeg|max:5148'
        ]);

        if ($request->hasFile("photos")) {
            DB::transaction(function () use ($vehicleData, $request) {
                $added_paths = [];
                try {
                    $user = auth()->user();
                    $vehicle = $user->vehicles()->create($vehicleData);
        
                    $files = $request->file("photos");
                    foreach ($files as $file) {
                        $path = $file->store("public");

                        array_push($added_paths, $path);

                        $vehicle->images()->create([
                            "image_path" => $path
                        ]);
                    }
    
                    $vehicle->load("images");
                    $vehicle->load("owner");
                    response()->json($vehicle, 201);
                } catch (Exception $e) {
                    foreach ($added_paths as $path) {
                        Storage::delete($path);
                    }
                    throw $e;
                }
            });
        } else {
            abort(422, "Missing photos");
        }
    }

    public function userVehicles(User $user)
    {
        return $user->vehicles()->with("images")->with("owner")->get();
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
            'photos.*' => 'mimes:jpg,png,jpeg|max:5148',
            'deletedPhotos.*' => 'numeric'
        ]);

        DB::transaction(function () use ($vehicleData, $request, $vehicle) {
            $vehicle->update(Arr::except($vehicleData, ["photos", "deletedPhotos"]));

            if ($request->hasFile("photos")) {
                $added_paths = [];
                try {
        
                    $files = $request->file("photos");
                    foreach ($files as $file) {
                        $path = $file->store("public");

                        array_push($added_paths, $path);

                        $vehicle->images()->create([
                            "image_path" => $path
                        ]);
                    }
    
                    $vehicle->load("images");
                    $vehicle->load("owner");
                    response()->json($vehicle, 201);
                } catch (Exception $e) {
                    foreach ($added_paths as $path) {
                        Storage::delete($path);
                    }
                    throw $e;
                }
            }

            if ($request->has("deletedPhotos")) {
                $vehicle->images()->whereIn('id', $vehicleData["deletedPhotos"])->delete();
            }
        });

        $vehicle->load("images");
        $vehicle->load("owner");

        return $vehicle;
    }
}
