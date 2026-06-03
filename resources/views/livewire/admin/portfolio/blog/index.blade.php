<div x-data="{ pickerOpen: false }" @keydown.escape.window="pickerOpen = false">
    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
        <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-gray-300 transition-colors">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-300">Blog</span>
    </div>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider">Blog Posts</h1>
            <p class="text-gray-500 mt-1">Manage your blog articles.</p>
        </div>
        <button type="button" @click="pickerOpen = true"
           class="bg-primary hover:bg-primary-hover text-white font-medium rounded-lg px-4 py-2.5 transition-colors text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Post
        </button>
    </div>

    {{-- Type-picker modal --}}
    <div x-show="pickerOpen"
         x-transition.opacity
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
         @click="pickerOpen = false">
        <div @click.stop
             x-show="pickerOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-dark-800 border border-dark-700 rounded-xl p-6 max-w-lg w-full mx-4 shadow-2xl">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-mono font-semibold text-white uppercase tracking-wider">Pick a Blog Type</h2>
                <button type="button" @click="pickerOpen = false"
                        class="text-gray-500 hover:text-white transition-colors p-1 rounded-lg hover:bg-dark-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Blog --}}
                <a href="{{ route('admin.blog.create') }}" wire:navigate
                   class="group flex flex-col items-center justify-center gap-2 bg-dark-700 hover:bg-dark-600 border border-dark-600 hover:border-primary/50 rounded-xl p-6 transition-all min-h-[150px] text-center">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10 text-primary-light group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                    <span class="text-sm font-mono font-semibold text-white uppercase tracking-wider">Blog</span>
                    <span class="text-xs text-gray-500">Heading, rich text &amp; code blocks</span>
                </a>

                {{-- Comparison (phase-2 stub) --}}
                <a href="{{ route('admin.blog.comparison.create') }}" wire:navigate
                   class="group flex flex-col items-center justify-center gap-2 bg-dark-700 hover:bg-dark-600 border border-dark-600 hover:border-fuchsia-500/50 rounded-xl p-6 transition-all min-h-[150px] text-center">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-fuchsia-500/10 text-fuchsia-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l9-4 9 4M3 6v12l9 4m-9-4 9 4 9-4M3 6l9 4m0 0v12m0-12l9-4m0 16V6"/></svg>
                    </span>
                    <span class="text-sm font-mono font-semibold text-white uppercase tracking-wider">Comparison</span>
                    <span class="text-xs text-gray-500">Coming soon</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search posts..."
                       class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
            </div>
            <select wire:model.live="visibilityFilter"
                    class="bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                <option value="all">All Visibility</option>
                <option value="public">Public</option>
                <option value="private">Private</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-dark-700/50">
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Title</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Visibility</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Tags</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Reading Time</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Views</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Published</th>
                        <th class="text-right text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-700">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-dark-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm text-white font-medium">{{ $post->title }}</div>
                                @if ($post->excerpt)
                                    <div class="text-xs text-gray-400 truncate max-w-[200px]">{{ Str::limit($post->excerpt, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" wire:click="toggleVisibility({{ $post->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium transition-colors {{ $post->visibility === 'public' ? 'bg-emerald-500 text-white hover:bg-emerald-600' : 'bg-dark-700 text-gray-300 hover:bg-dark-600 hover:text-white' }}"
                                        title="{{ $post->visibility === 'public' ? 'Public — click to make private' : 'Private — click to make public' }}">
                                    @if ($post->visibility === 'public')
                                        Public
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Private
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($post->tags->take(3) as $tag)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary-light">{{ $tag->tag }}</span>
                                    @endforeach
                                    @if ($post->tags->count() > 3)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-dark-700 text-gray-400">+{{ $post->tags->count() - 3 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">{{ $post->reading_time_minutes ?? 0 }} min read</td>
                            <td class="px-6 py-4 text-sm text-gray-400">{{ $post->view_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                @if ($post->published_at)
                                    {{ $post->published_at->format('M d, Y') }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.blog.preview', $post) }}" target="_blank"
                                       title="Preview" class="text-gray-400 hover:text-primary-light transition-colors p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.blog.edit', $post) }}" wire:navigate
                                       class="text-gray-400 hover:text-primary-light transition-colors p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <x-admin.confirm-button
                                        title="Delete Blog Post?"
                                        text="This blog post will be permanently removed."
                                        action="$wire.delete({{ $post->id }})"
                                        confirm-text="Yes, delete it"
                                        class="text-gray-400 hover:text-red-400 transition-colors p-1"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </x-admin.confirm-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No blog posts found. <a href="{{ route('admin.blog.create') }}" wire:navigate class="text-primary-light hover:underline">Create one</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($posts->hasPages())
            <div class="px-6 py-4 border-t border-dark-700">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
