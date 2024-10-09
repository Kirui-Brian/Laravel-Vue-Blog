<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Author;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Get all posts
     public function index()
    {
        $posts = Post::with(['author', 'tags'])->get();
        return response()->json($posts);
        // return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    // Create a new post
     public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'author_id' => 'required|exists:authors,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',

        ]);

        //$post = Post::create($validated); // Create the post after validation.
        $post = Post::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'author_id' => $validated['author_id'], // Store the author_id
        ]);

        if (!empty($validated['tags'])) {
             $post->tags()->sync($validated['tags']); // attach tags to the post
        }

        return response()->json($post->makeHidden(['author_id']));
    }

    // Show specific post
    public function show(string $id)
    {
        return Post::with(['author', 'tags'])->findOrFail($id);
    }

    // Update a specific post
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'author_id' => 'sometimes|required|exists:authors,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $post->update($request->all());

        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return response()->json(['message' => 'Post updated successfully', 'post' => $post], 200);

    }

    // Delete a specific post
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        if ($post->delete()) {
            return response()->json(null, 204);
        } else {
            return response()->json(['error' => 'Failed to delete the post'], 500);
        }
    }

    public function getAuthorPosts($id) {
        $author = Author::find($id);

        if(!$author) {
            return response()->json(['error' => 'Author not found.'], 404);
        }

       $posts = Post::where('author_id', $id)->get(); // Fetch posts for the given Author ID

       if ($posts->isEmpty()) {
        return response()->json(['message' => 'No posts found for this author.'], 404);
       } else {
        return response()->json([
            'name' => $author->name,
            'posts' => $posts,
        ]);
       }
    }
}
