<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ListingsController extends Controller
{
    public function show(Listing $listing)
    {
        return $listing;
    }

    public function createListing(Request $request)
    {
        $user = auth()->user();
        $listingData = $request->validate([
            'price' => 'required|numeric|max:999999999',
            'mileage' => 'required|numeric|max:999999999',
            'vehicle' => [
                'required',
                Rule::exists('vehicles', 'id')->where(function(Builder $query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
        ]);

        $listing = $user->listings()->make(Arr::except($listingData, 'vehicle'));
        $listing->vehicle()->associate($listingData['vehicle']);
        $listing->save();

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

        $user = auth()->user();
        $listingData = $request->validate([
            'price' => 'required|numeric|max:999999999',
            'mileage' => 'required|numeric|max:999999999',
        ]);

        $listing->update($listingData);

        return $listing;
    }

    public function changeListingToSold(Request $request, Listing $listing)
    {
        $user = auth()->user();
        if ($user->id != $listing->user_id) {
            abort(404, "Listing not found");
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
        $listing->vehicle->owner()->dissociate();
        $listing->vehicle->owner()->associate($newOwner);
        $listing->save();
        return ["message" => "Listing sold sucessfully"];
    }
}
