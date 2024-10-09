<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Post;
use App\Models\Tag;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 authors
        $authors = Author::factory(10)->create();

        // Create 5 tags
        $tags = Tag::factory(5)->create();

        // Create 20 posts and attach tags to them
        Post::factory(20)->create()->each(function ($post) use ($tags) {
            // Attach 1 to 3     random tags to each post (many-to-many relationship)
            $post->tags()->attach($tags->random(rand(1,3))->pluck('id')->toArray());
        });
    }
}
