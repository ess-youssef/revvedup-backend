<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path'
    ];

    protected $hidden = [
        "vehicle_id"
    ];
    
    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
