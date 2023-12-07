<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Api\PostResource;
use App\Http\Resources\Api\PostDetailResource;

class PostController extends Controller
{
    public function index() {
        $posts = Post::all();

        return PostResource::collection($posts);
    }

    public function show($id) {
        // $post = Post::where('id', $id)->get();
        $post = Post::with('writer:id,username,email')->findOrFail($id);

        return new PostDetailResource($post);
    }
}
