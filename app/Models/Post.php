<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "content",
    ];

    protected $appends = ['upvoted_by_user'];

    public function author() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function upvotes() {
        return $this->belongsToMany(User::class, PostUpvote::class);
    }

    public function getUpvotedByUserAttribute() {
        $user = Auth::user();
        if ($user == null) return false;
        $upvote = $this->upvotes()->where("user_id", $user->id)->first();
        return $upvote != null;
    }
}
