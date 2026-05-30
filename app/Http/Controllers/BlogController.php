<?php

namespace App\Http\Controllers;

use App\Models\Blog\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog.index', [
            'posts' => BlogPost::query()->published()->public()->latest('published_at')->paginate(12),
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::query()->published()->public()->where('slug', $slug)->with('tags')->firstOrFail();

        $post->timestamps = false;
        $post->increment('view_count');

        return view('blog.show', [
            'post' => $post,
            'isPreview' => false,
        ]);
    }

    /**
     * Admin-only preview — renders any post (draft / private) in the public
     * reading layout. Reached via an auth-protected admin route.
     */
    public function preview(BlogPost $blogPost)
    {
        $blogPost->load('tags');

        return view('blog.show', [
            'post' => $blogPost,
            'isPreview' => true,
        ]);
    }
}
