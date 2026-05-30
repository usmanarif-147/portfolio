@props(['post'])

@php
    $hasCover = filled($post->cover_image);
    $src = $hasCover
        ? (\Illuminate\Support\Str::startsWith($post->cover_image, 'http') ? $post->cover_image : asset('storage/'.$post->cover_image))
        : null;
@endphp

<a href="{{ route('blogs.show', $post->slug) }}"
   class="group flex h-full flex-col overflow-hidden rounded-2xl border border-white/[0.04] bg-dark-800 transition-all duration-300 hover:border-accent/20">
    <div class="relative overflow-hidden">
        @if($hasCover)
            <img src="{{ $src }}" alt="{{ $post->title }}" loading="lazy"
                 class="h-44 w-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="flex h-44 w-full items-center justify-center bg-gradient-to-br from-accent/15 via-fuchsia-600/10 to-dark-800">
                <svg class="h-10 w-10 text-accent/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-dark-800 to-transparent"></div>
    </div>
    <div class="flex flex-1 flex-col p-6">
        <div class="mb-3 flex items-center gap-3 text-xs text-gray-500">
            <span>{{ $post->published_at->format('M d, Y') }}</span>
            <span>&middot;</span>
            <span>{{ $post->reading_time_minutes ?? 1 }} min read</span>
            <span>&middot;</span>
            <span>{{ number_format($post->view_count ?? 0) }} views</span>
        </div>
        <h3 class="mb-2 text-lg font-bold text-white transition-colors group-hover:text-accent">{{ $post->title }}</h3>
        <p class="line-clamp-2 flex-1 text-sm text-gray-400">{{ $post->excerpt }}</p>
        <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-accent">
            Read article
            <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </span>
    </div>
</a>
