<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListingResource;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ListingsController extends Controller
{
    public function show(Listing $listing)
    {
        $listing->load("vehicle.images");
        $listing->load("author");
        return $listing;
    }

    public function userListings(User $user)
    {
        $userListings = $user->listings()->with("vehicle.images")->paginate(30);
        return ListingResource::collection($userListings);
    }

    public function list(Request $request)
    {
        $searchTerm = $request->query("search");
        $listings =
            $searchTerm
                ? Listing::with("vehicle.images")->search($searchTerm)->latest()->paginate(30)
                : Listing::with("vehicle.images")->latest()->paginate(30);
        return ListingResource::collection($listings);
    }

    public function createListing(Request $request)
    {
        $user = auth()->user();
        $listingData = $request->validate([
            'price' => 'required|numeric|max:999999999',
            'mileage' => 'required|numeric|max:999999999',
            'description' => 'required|max:255',
            'vehicle' => [
                'required',
                Rule::exists('vehicles', 'id')->where(function(Builder $query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
        ]);

        $existingListing = 
            Listing::where("user_id", $user->id)
                ->where("vehicle_id", $listingData["vehicle"])
                ->where("status", "FORSALE")->first();

        if ($existingListing) {
            abort(409, "You already listed this vehicle for sale");
        }

        $listing = $user->listings()->make(Arr::except($listingData, 'vehicle'));
        $listing->vehicle()->associate($listingData['vehicle']);
        $listing->save();

        $listing->load("vehicle.images");
        $listing->load("author");

        return response()->json($listing, 201);
    }
    
    public function deleteListing(Listing $listing)
    {
        $user = auth()->user();
        if ($user->id != $listing->user_id) {
            abort(404, "Listing not found");
        }
        $listing->delete();
        return ["message" => "Listing deleted sucessfully"];
    }

    public function editListing(Request $request, Listing $listing)
    {
        $user = auth()->user();

        if ($user->id != $listing->user_id) {
            abort(404, "Listing not found");
        }
        if ($listing->status === "SOLD") {
            abort(422, "Listing is already sold");
        }

        $listingData = $request->validate([
            'price' => 'required|numeric|max:999999999',
            'mileage' => 'required|numeric|max:999999999',
            'description' => 'required|max:255',
        ]);

        $listing->update($listingData);
        $listing->load("vehicle.images");
        $listing->load("author");

        return $listing;
    }

    public function changeListingToSold(Request $request, Listing $listing)
    {
        $user = auth()->user();
        if ($user->id != $listing->user_id) {
            abort(404, "Listing not found");
        }
        if ($listing->status === "SOLD") {
            abort(422, "Listing is already sold");
        }
        $saleData = $request->validate([
            'buyer' => [
                'required',
                Rule::exists('users', 'username')->where(function(Builder $query) use ($user) {
                    return $query->whereNot('username', $user->username);
                }),
            ],
        ]);
        
        $newOwner = User::where('username', $saleData['buyer'])->first();
        DB::transaction(function () use ($listing, $newOwner) {
            $listing->vehicle->owner()->dissociate();
            $listing->vehicle->owner()->associate($newOwner);
            $listing->status = "SOLD";
            $listing->vehicle->save();
            $listing->save();
        });
        return ["message" => "Listing sold sucessfully"];
    }
}
