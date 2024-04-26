<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

    public function allComments(Post $post)
    {
        $comments = $post->comments()->with("author")->paginate(30);
        return CommentResource::collection($comments);
    }

    public function showComment(Post $post, Comment $comment)
    { 
        if ($post->id != $comment->post_id) {
            abort(404, "Comment not found"); 
        }
        $comment->load("author");
        return $comment;
    }

    public function createComment(Post $post, Request $request)
    {

        $commentData = $request->validate([
            'content' => 'required',
        ]);

        $user = auth()->user();
        $comment = $user->comments()->make($commentData);
        $comment->post()->associate($post);
        $comment->save();
        $comment->load("author");
    
        return response()->json($comment, 201);
    }

    public function deleteComment(Post $post, Comment $comment)
    {
        $user = auth()->user();
        if ($user->id != $comment->user_id) {
            abort(403, "Forbidden");
        }

        if ($post->id != $comment->post_id) {
            abort(404, "Comment not found"); 
        }

        $comment->delete();

        return ["message" => "comment deleted sucessfully"];
    }

    
    public function editComment(Request $request, Post $post, Comment $comment)
    {
        $user = auth()->user();

        if ($user->id != $comment->user_id) {
            abort(404, "Something went wrong");
        }

        if ($post->id != $comment->post_id) {
            abort(404, "Comment not found"); 
        }

        $commentData = $request->validate([
            'content' => 'required',
        ]);

        $comment->update($commentData);
        $comment->load("author");

        return $comment;
    }

    public function toggleUpvoteComment(Post $post, Comment $comment)
    {
        if ($post->id != $comment->post_id) {
            abort(404, "Comment not found"); 
        }

        $user = auth()->user();
        $user->upvotes()->toggle($comment->id);
        return ["message" => "Toggled upvote sucessfully"];
    }
} 