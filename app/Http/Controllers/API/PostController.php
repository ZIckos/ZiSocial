<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    /* 
    On récupère les 3 derniers posts
    */
    public function getLatestPosts()
    {
        $posts = Post::latest()->take(3)->get();
        return response()->json($posts);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('comments')->latest()->get();
        return response()->json($posts);
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'required|string',
        ]);
        $post = Post::create([
            'content' => $validated['content'],
            'tags' => $validated['tags'],
            'user_id' => Auth::id(),
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $post->update(['image' => $imageName]);
        }

        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load('comments'); // Charger les commentaires associés
        return response()->json($post);
    }


    /**
     * Update the specified resource in storage.
     */
    // app/Http/Controllers/API/PostController.php

    public function update(Request $request, Post $post)
    {
        // Vérifier si l'utilisateur authentifié est le propriétaire du post
        if (Auth::id() !== $post->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
        ]);

        if ($request->has('content')) {
            $post->content = $validated['content'];
        }

        if ($request->has('tags')) {
            $post->tags = $validated['tags'];
        }

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $post->update(['image' => $imageName]);
        }

        $post->save();

        return response()->json($post);
    }







    public function destroy(Post $post)
    {
        // Vérifier si l'utilisateur authentifié est le propriétaire du post
        if (Auth::id() !== $post->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
