<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = ['title', 'body', 'author_id'];

    // Define relationships
    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
