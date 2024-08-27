<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($postId)
    {
        $comments = Comment::where('post_id', $postId)
                            ->with('user') 
                            ->get();

        return response()->json($comments);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $postId)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post = Post::findOrFail($postId);

        $comment = new Comment([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        $comment->save();

        return response()->json([
            'comment' => $comment,
            'message' => 'Commentaire ajouté avec succès',
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        $comment->load('user', 'post'); // Charger les relations si nécessaire
        return response()->json($comment);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
            'image' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        if ($request->has('content')) {
            $comment->content = $validated['content'];
        }

        if ($request->has('image')) {
            $comment->image = $validated['image'];
        }

        if ($request->has('tags')) {
            $comment->tags = $validated['tags'];
        }

        $comment->save();

        return response()->json($comment);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
