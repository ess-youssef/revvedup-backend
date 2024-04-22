<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VehiclesController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::post("login", [AuthController::class, "login"])->name("login");
    Route::post("logout", [AuthController::class, "logout"])->middleware("auth:sanctum")->name("logout");
});

Route::prefix("users")->group(function () {
    Route::post("register", [UsersController::class, "register"])->name("register");
    Route::get("{user}", [UsersController::class, "show"])->middleware("auth:sanctum")->name("users.show");
    Route::put("{user}", [UsersController::class, "edit"])->middleware("auth:sanctum")->name("users.edit");

    Route::get("{user}/vehicles", [VehiclesController::class, "userVehicles"])->name("user.vehicles");
});

Route::prefix("vehicles")->group(function () {
    Route::get("{vehicle}", [VehiclesController::class, "show"])->name("vehicles.show");
    Route::post("register", [VehiclesController::class, "registerVehicle"])->middleware("auth:sanctum")->name("vehicles.register");
    Route::delete("{vehicle}", [VehiclesController::class, "deleteUserVehicle"])->middleware("auth:sanctum")->name("vehicles.delete");
    Route::put("{vehicle}", [VehiclesController::class, "editVehicle"])->middleware("auth:sanctum")->name("vehicles.edit");
});