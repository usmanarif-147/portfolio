<?php

namespace Database\Seeders;

use App\Models\Blog\BlogPost;
use App\Models\Blog\BlogPostTag;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $post1 = BlogPost::create([
            'title' => 'Building Scalable Laravel Applications with Livewire',
            'slug' => 'building-scalable-laravel-applications-with-livewire',
            'excerpt' => 'Learn how to architect Laravel applications that scale gracefully using Livewire components and best practices.',
            'content' => '<p>Laravel and Livewire together form a powerful combination for building modern web applications without the complexity of a separate frontend framework.</p><p>In this article, we explore patterns for building scalable, maintainable Livewire applications.</p>',
            'status' => 'published',
            'published_at' => '2024-02-15 10:00:00',
            'reading_time_minutes' => 8,
        ]);

        BlogPostTag::create(['blog_post_id' => $post1->id, 'tag' => 'Laravel']);
        BlogPostTag::create(['blog_post_id' => $post1->id, 'tag' => 'Livewire']);

        $post2 = BlogPost::create([
            'title' => 'Optimizing Database Queries in Large-Scale Applications',
            'slug' => 'optimizing-database-queries-large-scale',
            'excerpt' => 'Practical tips for identifying and fixing N+1 queries, using eager loading, and leveraging database indexes.',
            'content' => '<p>Performance optimization is crucial for applications handling thousands of users. This guide covers the most impactful database optimizations you can make in Laravel.</p>',
            'status' => 'published',
            'published_at' => '2024-01-10 14:00:00',
            'reading_time_minutes' => 6,
        ]);

        BlogPostTag::create(['blog_post_id' => $post2->id, 'tag' => 'Performance']);
        BlogPostTag::create(['blog_post_id' => $post2->id, 'tag' => 'Database']);
    }
}
