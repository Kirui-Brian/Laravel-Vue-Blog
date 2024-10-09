<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    // Get all authors
    public function index()
    {
        return Author::all();
    }

    // Create new author
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return Author::create($validated);
    }

    // Show specific author
    public function show(string $id)
    {
        return Author::findOrFail($id);
    }

    // Update a specific author
    public function update(Request $request, string $id)
    {
        $author = Author::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $author->update($validated);

        return response()->json($author);
    }

    // Delete a specific author
    public function destroy(string $id)
    {
        $author = Author::findOrFail($id);
        $author->delete();

        return response()->json(null, 204);
    }
}
