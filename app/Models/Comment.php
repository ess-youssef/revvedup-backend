<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        "content",
    ]; 

    protected $hidden = [
        'user_id',
        'post_id',
        'parent_comment_id',
    ];

    protected $appends = ['upvoted_by_user'];

    public function author() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function parent() {
        return $this->belongsTo(Comment::class, "parent_comment_id");
    }

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function upvotes() {
        return $this->belongsToMany(User::class, CommentUpvote::class);
    }

    public function getUpvotedByUserAttribute() {
        $user = Auth::user();
        if ($user == null) return false;
        $upvote = $this->upvotes()->where("user_id", $user->id)->first();
        return $upvote != null;
    }
}
