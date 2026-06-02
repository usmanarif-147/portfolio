<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'content_html',
        'toc',
        'blocks',
        'type',
        'status',
        'visibility',
        'published_at',
        'meta_title',
        'meta_description',
        'reading_time_minutes',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'toc' => 'array',
            'blocks' => 'array',
            'published_at' => 'datetime',
            'reading_time_minutes' => 'integer',
            'view_count' => 'integer',
        ];
    }

    public function tags(): HasMany
    {
        return $this->hasMany(BlogPostTag::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('visibility', 'public');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function calculateReadingTime(): int
    {
        return (int) ceil(str_word_count(strip_tags($this->content)) / 200);
    }
}
