<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "price" => $this->price,
            "status" => $this->status,
            "mileage" => $this->mileage,
            "vehicle" => $this->whenLoaded("vehicle"),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
