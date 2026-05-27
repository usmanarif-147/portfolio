<?php

namespace App\Http\Controllers;

use App\Models\Blog\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog.index', [
            'posts' => BlogPost::query()->published()->latest('published_at')->paginate(12),
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::query()->published()->where('slug', $slug)->with('tags')->firstOrFail();

        return view('blog.show', [
            'post' => $post,
        ]);
    }
}
