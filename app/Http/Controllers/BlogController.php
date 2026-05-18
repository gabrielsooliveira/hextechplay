<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)->with('category')->latest()->paginate(10);
        return Inertia::render('Blog/Index', [
            'posts' => $posts
        ]);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->with('category')->firstOrFail();
        return Inertia::render('Blog/Show', [
            'post' => $post
        ]);
    }
}
