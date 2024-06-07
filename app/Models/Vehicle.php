<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        "make",
        "model",
        "year",
        "description"
    ];

    protected $hidden = [
        'user_id',
    ];

    protected $casts = [
        'year' => 'int',
    ];
    
    public function listings() {
        return $this->hasMany(Listing::class);
    }

    
    public function images() {
        return $this->hasMany(VehicleImage::class);
    }

    
    public function owner() {
        return $this->belongsTo(User::class, "user_id");
    }
}
