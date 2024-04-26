<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "content",
    ];

    public function author() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function upvotes() {
        return $this->belongsToMany(User::class, PostUpvote::class);
    }
}
