<div>
    {{-- Trix assets --}}
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.8/dist/trix.css">
    <style>
        /* Dark-theme Trix to match the admin palette */
        trix-editor {
            background-color: #1a1a24;
            border: 1px solid #25253a;
            border-top: none;
            color: #e5e7eb;
            min-height: 180px;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            line-height: 1.7;
        }
        trix-editor:focus { outline: none; box-shadow: 0 0 0 2px #7c3aed; }
        trix-toolbar {
            background-color: #12121a;
            border: 1px solid #25253a;
            border-bottom: none;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            padding: 0.4rem 0.5rem;
        }
        trix-toolbar .trix-button-row { display: flex; flex-wrap: wrap; gap: 2px; }
        trix-toolbar .trix-button {
            background: transparent;
            border: none;
            color: #9ca3af;
            border-radius: 0.35rem;
            padding: 0.3rem 0.45rem;
            transition: background 0.15s, color 0.15s;
        }
        trix-toolbar .trix-button:hover,
        trix-toolbar .trix-button.trix-active {
            background: #25253a;
            color: #fff;
        }
        trix-toolbar .trix-button-group { border: none; margin: 0; }
        trix-toolbar .trix-button-group--file-tools { display: none; }
        trix-toolbar .trix-button--icon::before { filter: invert(60%); }
        trix-toolbar .trix-button--icon.trix-active::before,
        trix-toolbar .trix-button--icon:hover::before { filter: invert(90%); }
    </style>
    <script src="https://unpkg.com/trix@2.1.8/dist/trix.umd.min.js"></script>

    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
        <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-gray-300 transition-colors">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('admin.blog.index') }}" wire:navigate class="hover:text-gray-300 transition-colors">Blog</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-300">{{ $blogPost ? 'Edit' : 'Create' }} Post</span>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider">{{ $blogPost ? 'Edit Post' : 'Create Post' }}</h1>
        <p class="text-gray-500 mt-1">{{ $blogPost ? 'Update your blog post.' : 'Write a new blog article.' }}</p>
    </div>

    <div class="max-w-3xl space-y-6">
        {{-- Section 1 — Basic Info --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-5">
            <h2 class="text-lg font-mono font-semibold text-white uppercase tracking-wider">Basic Info</h2>

            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-1.5">Title <span class="text-red-400">*</span></label>
                <input type="text" id="title" wire:model="title"
                       class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="e.g. Getting Started with Laravel">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-300 mb-1.5">Excerpt</label>
                <textarea id="excerpt" wire:model="excerpt" rows="2" maxlength="500"
                          class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                          placeholder="A brief summary of the post..."></textarea>
                @error('excerpt') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section 2 — Content Blocks --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-mono font-semibold text-white uppercase tracking-wider">Content</h2>
                <span class="text-xs text-gray-500">{{ count($blocks) }} {{ Str::plural('block', count($blocks)) }}</span>
            </div>

            @error('blocks') <p class="text-sm text-red-400">{{ $message }}</p> @enderror

            {{-- Block list --}}
            <div class="space-y-4">
                @forelse($blocks as $i => $block)
                    <div wire:key="block-{{ $block['id'] }}" class="bg-dark-900 border border-dark-700 rounded-xl p-5 space-y-4">

                        {{-- Block header --}}
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 text-xs font-mono uppercase tracking-wider text-gray-500">
                                @if($block['type'] === 'heading')
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h10"/></svg>
                                    Heading
                                @elseif($block['type'] === 'richtext')
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Rich Text
                                @elseif($block['type'] === 'code')
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                    Code
                                @endif
                            </span>
                            <div class="flex items-center gap-1">
                                <button type="button" wire:click="moveBlock('{{ $block['id'] }}', 'up')"
                                        title="Move up"
                                        class="text-gray-500 hover:text-gray-300 transition-colors p-1 rounded hover:bg-dark-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                </button>
                                <button type="button" wire:click="moveBlock('{{ $block['id'] }}', 'down')"
                                        title="Move down"
                                        class="text-gray-500 hover:text-gray-300 transition-colors p-1 rounded hover:bg-dark-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <button type="button" wire:click="removeBlock('{{ $block['id'] }}')"
                                        title="Remove block"
                                        class="text-gray-500 hover:text-red-400 transition-colors p-1 rounded hover:bg-dark-700 ml-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Heading block --}}
                        @if($block['type'] === 'heading')
                            <div class="flex gap-3">
                                <div class="shrink-0">
                                    <label class="block text-xs text-gray-500 mb-1">Level</label>
                                    <select wire:model="blocks.{{ $i }}.level"
                                            class="bg-dark-700 border border-dark-600 rounded-lg px-3 py-2.5 text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="2">H2</option>
                                        <option value="3">H3</option>
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-500 mb-1">Text</label>
                                    <input type="text"
                                           wire:model.live.debounce.400ms="blocks.{{ $i }}.text"
                                           class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent {{ $block['level'] == 2 ? 'text-xl font-bold' : 'text-lg font-semibold' }}"
                                           placeholder="Heading text…">
                                </div>
                            </div>

                        {{-- Rich text block --}}
                        @elseif($block['type'] === 'richtext')
                            <div wire:ignore
                                 x-data
                                 x-on:trix-initialize="$el.querySelector('trix-editor').editor.loadHTML(@js($block['html'] ?? ''))"
                                 x-on:trix-change="$wire.updateRichText('{{ $block['id'] }}', $event.target.value)">
                                <trix-editor class="trix-content"></trix-editor>
                            </div>

                        {{-- Code block --}}
                        @elseif($block['type'] === 'code')
                            <div class="space-y-3">
                                <div class="flex gap-3">
                                    <div class="shrink-0">
                                        <label class="block text-xs text-gray-500 mb-1">Language</label>
                                        <select wire:model="blocks.{{ $i }}.language"
                                                class="bg-dark-700 border border-dark-600 rounded-lg px-3 py-2.5 text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="plaintext">Plain Text</option>
                                            <option value="php">PHP</option>
                                            <option value="bash">Bash</option>
                                            <option value="javascript">JavaScript</option>
                                            <option value="json">JSON</option>
                                            <option value="yaml">YAML</option>
                                            <option value="html">HTML</option>
                                            <option value="css">CSS</option>
                                            <option value="sql">SQL</option>
                                            <option value="blade">Blade</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs text-gray-500 mb-1">Filename <span class="text-gray-600">(optional)</span></label>
                                        <input type="text"
                                               wire:model="blocks.{{ $i }}.filename"
                                               class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent text-sm font-mono"
                                               placeholder="app/Models/User.php (optional)">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Code</label>
                                    <textarea rows="8"
                                              wire:model.lazy="blocks.{{ $i }}.code"
                                              class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent font-mono text-sm resize-y leading-relaxed"
                                              placeholder="Paste your code here…"></textarea>
                                </div>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 border border-dashed border-dark-600 rounded-xl text-center gap-2">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm text-gray-500">No blocks yet. Add one below to start writing.</p>
                    </div>
                @endforelse
            </div>

            {{-- Add-block toolbar --}}
            <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-dark-700">
                <span class="text-xs text-gray-500 mr-1">Add block:</span>
                <button type="button" wire:click="addBlock('heading')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-dark-700 border border-dark-600 text-gray-300 hover:bg-dark-600 hover:text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h10"/></svg>
                    Heading
                </button>
                <button type="button" wire:click="addBlock('richtext')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-dark-700 border border-dark-600 text-gray-300 hover:bg-dark-600 hover:text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Rich Text
                </button>
                <button type="button" wire:click="addBlock('code')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-dark-700 border border-dark-600 text-gray-300 hover:bg-dark-600 hover:text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    Code
                </button>
            </div>
        </div>

        {{-- Section 3 — Cover Image --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-5">
            <h2 class="text-lg font-mono font-semibold text-white uppercase tracking-wider">Cover Image</h2>

            <div>
                @if ($existingCoverImage)
                    <div class="mb-3 relative inline-block">
                        <img src="{{ Storage::url($existingCoverImage) }}" alt="Cover image" class="w-40 h-28 rounded-lg object-cover">
                        <button type="button" wire:click="removeCoverImage"
                                class="absolute -top-2 -right-2 bg-dark-900 border border-dark-600 rounded-full p-1 text-gray-400 hover:text-red-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @if ($coverImage)
                    <div class="mb-3 relative inline-block">
                        <img src="{{ $coverImage->temporaryUrl() }}" alt="Cover preview" class="w-40 h-28 rounded-lg object-cover">
                        <button type="button" wire:click="$set('coverImage', null)"
                                class="absolute -top-2 -right-2 bg-dark-900 border border-dark-600 rounded-full p-1 text-gray-400 hover:text-red-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @if (!$coverImage && !$existingCoverImage)
                    <input type="file" wire:model="coverImage" accept="image/*"
                           class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-primary/10 file:text-primary-light hover:file:bg-primary/20">
                @endif

                @error('coverImage') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section 4 — Tags --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-5">
            <h2 class="text-lg font-mono font-semibold text-white uppercase tracking-wider">Tags</h2>

            <div class="flex gap-2">
                <input type="text" wire:model="tagInput" wire:keydown.enter.prevent="addTag"
                       class="flex-1 bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                       placeholder="e.g. Laravel, PHP, Tutorial">
                <button type="button" wire:click="addTag"
                        class="bg-primary hover:bg-primary-hover text-white font-medium rounded-lg px-4 py-2.5 transition-colors text-sm">
                    Add
                </button>
            </div>
            @error('tagInput') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror

            @if (count($tags) > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($tags as $index => $tag)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm bg-primary/10 text-primary-light">
                            {{ $tag }}
                            <button type="button" wire:click="removeTag({{ $index }})" class="text-primary-light/60 hover:text-red-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                    @endforeach
                </div>
            @endif
            @error('tags') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- Section 5 — SEO --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-5">
            <h2 class="text-lg font-mono font-semibold text-white uppercase tracking-wider">SEO</h2>

            <div>
                <label for="meta_title" class="block text-sm font-medium text-gray-300 mb-1.5">Meta Title</label>
                <input type="text" id="meta_title" wire:model="meta_title" maxlength="255"
                       class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="SEO title (defaults to post title if empty)">
                @error('meta_title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="meta_description" class="block text-sm font-medium text-gray-300 mb-1.5">Meta Description</label>
                <textarea id="meta_description" wire:model="meta_description" rows="2" maxlength="500"
                          class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                          placeholder="Brief description for search engines..."></textarea>
                @error('meta_description') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section 6 — Visibility --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-4">
            <h2 class="text-lg font-mono font-semibold text-white uppercase tracking-wider">Visibility</h2>
            <p class="text-sm text-gray-500">Private posts never appear on the public site — only you can read them via the admin Preview.</p>

            <div class="flex flex-col sm:flex-row gap-3">
                <label class="flex-1 flex items-start gap-3 p-4 rounded-lg border cursor-pointer transition-colors {{ $visibility === 'public' ? 'border-primary bg-primary/5' : 'border-dark-600 hover:bg-dark-700/50' }}">
                    <input type="radio" wire:model.live="visibility" value="public" class="mt-1 accent-[#7c3aed]">
                    <span>
                        <span class="block text-sm font-medium text-white">Public</span>
                        <span class="block text-xs text-gray-500 mt-0.5">Shown on your portfolio's blog when published.</span>
                    </span>
                </label>
                <label class="flex-1 flex items-start gap-3 p-4 rounded-lg border cursor-pointer transition-colors {{ $visibility === 'private' ? 'border-primary bg-primary/5' : 'border-dark-600 hover:bg-dark-700/50' }}">
                    <input type="radio" wire:model.live="visibility" value="private" class="mt-1 accent-[#7c3aed]">
                    <span>
                        <span class="block text-sm font-medium text-white">Private</span>
                        <span class="block text-xs text-gray-500 mt-0.5">Only for you — never visible to visitors.</span>
                    </span>
                </label>
            </div>
            @error('visibility') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            @if($blogPost && $blogPost->status === 'published')
                <button wire:click="saveDraft"
                        class="bg-transparent border border-dark-600 text-gray-300 hover:bg-dark-700 font-medium rounded-lg px-6 py-2.5 transition-colors flex items-center gap-2">
                    <span wire:loading.remove wire:target="saveDraft">Unpublish &amp; Save Draft</span>
                    <span wire:loading wire:target="saveDraft" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Saving...
                    </span>
                </button>
            @else
                <button wire:click="saveDraft"
                        class="bg-transparent border border-dark-600 text-gray-300 hover:bg-dark-700 font-medium rounded-lg px-6 py-2.5 transition-colors flex items-center gap-2">
                    <span wire:loading.remove wire:target="saveDraft">Save Draft</span>
                    <span wire:loading wire:target="saveDraft" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Saving...
                    </span>
                </button>
                <button wire:click="publish"
                        class="bg-primary hover:bg-primary-hover text-white font-medium rounded-lg px-6 py-2.5 transition-colors flex items-center gap-2">
                    <span wire:loading.remove wire:target="publish">Publish</span>
                    <span wire:loading wire:target="publish" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Publishing...
                    </span>
                </button>
            @endif
            @if($blogPost)
                <a href="{{ route('admin.blog.preview', $blogPost) }}" target="_blank"
                   class="inline-flex items-center gap-2 text-gray-300 hover:text-white font-medium rounded-lg px-4 py-2.5 transition-colors border border-dark-600 hover:bg-dark-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview
                </a>
            @endif
            <a href="{{ route('admin.blog.index') }}" wire:navigate
               class="text-gray-400 hover:text-white font-medium rounded-lg px-6 py-2.5 transition-colors">
                Cancel
            </a>
        </div>
    </div>
</div>
