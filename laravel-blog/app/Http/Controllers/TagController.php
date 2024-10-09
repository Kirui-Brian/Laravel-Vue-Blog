<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // Get all tags
    public function index()
    {
        return Tag::all();
    }

    // Create a new tag
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return Tag::create($validated);
    }

    // Show a specific tag
    public function show(string $id)
    {
        $tag = Tag::findOrFail($id);
    }

    // Update a specific tag
    public function update(Request $request, string $id)
    {
        $tag = Tag::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $tag->update($validated);

        return response()->json($tag);
    }

    // Delete a specific tag
    public function destroy(string $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return response()->json(null, 204);
    }
}
