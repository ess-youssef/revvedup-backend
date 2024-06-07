<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{

    public function userPosts(User $user) {
        $posts = $user->posts()->with("author")->withCount("comments")->withCount("upvotes")->paginate(30);
        return PostResource::collection($posts);
    }

    public function list(Request $request)
    {
        if ($request->bearerToken()) {
            $user = Auth::guard("sanctum")->user();
            if ($user) {
                Auth::setUser($user);
            }
        }
        $posts = Post::with("author")->withCount("comments")->withCount("upvotes")->latest()->paginate(30);
        return PostResource::collection($posts);
    }

    public function show(Request $request, Post $post)
    {
        if ($request->bearerToken()) {
            $user = Auth::guard("sanctum")->user();
            if ($user) {
                Auth::setUser($user);
            }
        }
        $post->load("author");
        $post->loadCount("comments");
        $post->loadCount("upvotes");
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
        $post->loadCount("comments");
        $post->loadCount("upvotes");
    
        return response()->json($post, 201);
    }

    public function deletePost(Post $post)
    {
        $user = auth()->user();
        if ($user->id != $post->user_id) {
            abort(404, "Something went wrong");
        }
        $post->delete();
        return ["message" => "Post deleted sucessfully"];
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
        $post->loadCount("comments");
        $post->loadCount("upvotes");

        return $post;
    }

    public function toggleUpvotePost(Post $post)
    {
        $user = auth()->user();
        $user->upvotes()->toggle($post->id);
        return ["message" => "Toggled up vote sucessfully"];
    }
}
