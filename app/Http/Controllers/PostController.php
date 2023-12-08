<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Api\PostResource;
use App\Http\Resources\Api\PostDetailResource;

class PostController extends Controller
{
    public function index() {
        $posts = Post::all();

        return PostDetailResource::collection($posts->loadMissing(['writer:id,username', 'comments:id,post_id,user_id,comments_content']));
    }

    public function show($id) {
        // $post = Post::where('id', $id)->get();
        $post = Post::with('writer:id,username,email')->findOrFail($id);

        return new PostDetailResource($post->loadMissing(['writer:id,username', 'comments:id,post_id,user_id,comments_content']));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);

        if($request->image) {
            $fileName = $this->generateRandomString();
            $extension = $request->image->extension();

            Storage::putFileAs('post-images', $request->image, $fileName . '.' . $extension);
            $validated['image'] = $fileName . '.' . $extension;
        }

        $validated['author'] = Auth::user()->id;

        $post = Post::create($validated);
        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function destroy($id) {
        $post = Post::findOrFail($id);
        $post->delete();

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    // Php Random String Generator
    function generateRandomString($length = 30) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
