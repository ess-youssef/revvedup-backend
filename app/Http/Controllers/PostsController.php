<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{

    public function list()
    {
        $posts = Post::paginate(30)->with("author");
        return PostResource::collection($posts);
    }

    public function show(Post $post)
    {
        $post->load("author");
        return $post;
    }

    public function createPost(Request $request)
    {
        $postData = $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $user = auth()->user();
        $post = $user->posts()->create($postData);
        $post->load("author");
    
        return response()->json($post, 201);
    }

    public function deletePost(Post $post)
    {
        $user = auth()->user();
        if ($user->id != $post->user_id) {
            abort(404, "Something went wrong");
        }
        $post->delete();
        return ["message" => "post deleted sucessfully"];
    }

    public function editPost(Request $request, Post $post)
    {
        $user = auth()->user();

        if ($user->id != $post->user_id) {
            abort(404, "Something went wrong");
        }

        $postData = $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $post->update($postData);
        $post->load("author");

        return $post;
    }

    public function toggleUpvotePost(Post $post)
    {
        $user = auth()->user();
        $user->upvotes()->toggle($post->id);
        return ["message" => "Toggled up vote sucessfully"];
    }
}
