<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::post("login", [AuthController::class, "login"])->name("login");
    Route::post("logout", [AuthController::class, "logout"])->middleware("auth:sanctum")->name("logout");
});

Route::prefix("users")->group(function () {
    Route::post("register", [UsersController::class, "register"])->name("register");
    Route::get("{user}", [UsersController::class, "show"])->middleware("auth:sanctum")->name("users.show");
    Route::put("{user}", [UsersController::class, "edit"])->middleware("auth:sanctum")->name("users.edit");
});