<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        "price",
        "mileage",
        "description"
    ];

    protected $hidden = [
        'user_id',
        'vehicle_id',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function author() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeSearch(Builder $query, string $search): void {
        $query->whereHas("vehicle", function ($query) use ($search) {
            return $query
                ->where('model', 'LIKE', '%' . $search . '%')
                ->orWhere('make', 'LIKE', '%' . $search . '%')
                ->orWhere('year', 'LIKE', '%' . $search . '%');
        });
    }
}
