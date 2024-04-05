<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function author() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function parent() {
        return $this->belongsTo(Comment::class);
    }

    public function upvotes() {
        return $this->belongsToMany(User::class, CommentUpvote::class);
    }
}
