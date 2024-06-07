<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "start_date",
        "end_date",
        "location",
    ];

    protected $appends = ['attended_by_user'];

    public function attendance() {
        return $this->belongsToMany(User::class, EventAttendance::class);
    }

    public function getAttendedByUserAttribute() {
        $user = Auth::user();
        if ($user == null) return false;
        $attendance = $this->attendance()->where("user_id", $user->id)->first();
        return $attendance != null;
    }
}
