<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ListingsController;
use App\Http\Controllers\PostsController;
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

Route::prefix("listings")->group(function () {
    Route::get("{listing}", [ListingsController::class, "show"])->name("listings.show");
    Route::get("/", [ListingsController::class, "list"])->name("listings.list");
    Route::post("create", [ListingsController::class, "createListing"])->middleware("auth:sanctum")->name("listing.create");
    Route::delete("{listing}", [ListingsController::class, "deleteListing"])->middleware("auth:sanctum")->name("listing.delete");
    Route::put("{listing}", [ListingsController::class, "editListing"])->middleware("auth:sanctum")->name("listing.edit");
    Route::post("{listing}/sell", [ListingsController::class, "changeListingToSold"])->middleware("auth:sanctum")->name("listing.sell");
});

Route::prefix("posts")->group(function () {
    Route::get("{post}", [PostsController::class, "show"])->name("post.show");
    Route::get("/", [PostsController::class, "list"])->name("posts.list");
    Route::post("/", [PostsController::class, "createPost"])->middleware("auth:sanctum")->name("post.create");
    Route::delete("{post}", [PostsController::class, "deletePost"])->middleware("auth:sanctum")->name("post.delete");
    Route::put("{post}", [PostsController::class, "editPost"])->middleware("auth:sanctum")->name("post.edit");
    Route::post("{post}/toggle-upvote", [PostsController::class, "toggleUpvotePost"])->middleware("auth:sanctum")->name("post.upvote");
    Route::get("{post}/comments/{comment}", [CommentsController::class, "showComment"])->name("comment.show");
    Route::get("{post}/comments", [CommentsController::class, "allComments"])->name("comments.list");
    Route::post("{post}/comments", [CommentsController::class, "createComment"])->middleware("auth:sanctum")->name("comment.create");
    Route::delete("{post}/comments/{comment}", [CommentsController::class, "deleteComment"])->middleware("auth:sanctum")->name("comment.delete");
    Route::put("{post}/comments/{comment}", [CommentsController::class, "editComment"])->middleware("auth:sanctum")->name("comment.edit");
    Route::post("{post}/comments/{comment}/toggle-upvote", [CommentsController::class, "toggleUpvoteComment"])->middleware("auth:sanctum")->name("coment.upvote");
});
