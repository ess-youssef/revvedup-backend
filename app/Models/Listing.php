<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;


    public function author() {
        return $this->belongsTo(User::class);
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
