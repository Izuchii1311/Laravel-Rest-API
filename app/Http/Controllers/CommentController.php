<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comments_content' => 'required'
        ]);

        $validated['user_id'] = auth()->user()->id;
        $comment = Comment::create($validated);

        return new CommentResource($comment->loadMissing(['comentator:id,username,created_at']));
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'comments_content' => 'required'
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update($validated);

        return new CommentResource($comment->loadMissing(['comentator:id,username,created_at']));
    }

    public function destroy($id) {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return new CommentResource($comment->loadMissing('comentator:id,username,created_at'));
    }
}
