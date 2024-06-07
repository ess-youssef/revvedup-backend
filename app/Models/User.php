<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'gender',
        'username',
        'role',
        'profile_picture',
        'bio',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    
    public function comments() {
        return $this->hasMany(Comment::class);
    }

    
    public function vehicles() {
        return $this->hasMany(Vehicle::class);
    }

    
    public function events() {
        return $this->belongsToMany(Event::class, EventAttendance::class);
    }

    public function listings() {
        return $this->hasMany(Listing::class);
    }

    public function upvotes() {
        return $this->belongsToMany(Post::class, PostUpvote::class);
    }

    // SELECT * FROM users WHERE firstname LIKE '%youssef%'
    public function scopeSearch(Builder $query, string $search): void {
        $query
            ->where('firstname', 'LIKE', '%' . $search . '%')
            ->orWhere('lastname', 'LIKE', '%' . $search . '%')
            ->orWhere('username', 'LIKE', '%' . $search . '%');
    }
}
