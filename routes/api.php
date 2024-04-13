<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::post("login", [AuthController::class, "login"])->name("login");
    Route::post("logout", [AuthController::class, "logout"])->middleware("auth:sanctum")->name("logout");

});