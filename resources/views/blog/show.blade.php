<x-layouts.app>
    <x-public.top-nav active="blog" />

    {{-- highlight.js (syntax highlighting for code blocks) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/styles/atom-one-dark.min.css">
    <script src="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/highlight.min.js"></script>

    @php
        $hasCover = filled($post->cover_image);
        $coverSrc = $hasCover
            ? (\Illuminate\Support\Str::startsWith($post->cover_image, 'http') ? $post->cover_image : asset('storage/'.$post->cover_image))
            : null;
        $toc = $post->toc ?? [];
    @endphp

    <div x-data="articleReader()">

        {{-- Scroll progress bar --}}
        <div class="fixed inset-x-0 top-0 z-[60] h-1 bg-transparent">
            <div class="h-full bg-gradient-to-r from-accent via-fuchsia-500 to-orange-500"
                 :style="`width: ${progress}%`"></div>
        </div>

        @if($isPreview)
            <div class="fixed inset-x-0 top-1 z-50 bg-amber-500/15 border-b border-amber-500/30 px-4 py-2 text-center text-xs text-amber-300">
                Preview mode — <span class="font-semibold capitalize">{{ $post->status }}</span> ·
                <span class="font-semibold capitalize">{{ $post->visibility }}</span>. Not visible to visitors.
            </div>
        @endif

        <article class="min-h-screen px-4 pb-24 pt-28 sm:px-6">
            <div class="mx-auto max-w-6xl lg:grid lg:grid-cols-[minmax(0,1fr)_15rem] lg:gap-12">

                {{-- Main column --}}
                <div class="mx-auto w-full max-w-3xl">

                    <a href="{{ route('blogs.index') }}"
                       class="mb-8 inline-flex items-center gap-2 text-sm text-gray-500 transition-colors hover:text-accent">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Blog
                    </a>

                    @if($hasCover)
                        <div class="mb-8 overflow-hidden rounded-2xl border border-white/[0.04]">
                            <img src="{{ $coverSrc }}" alt="{{ $post->title }}" class="h-64 w-full object-cover md:h-80">
                        </div>
                    @endif

                    @if($post->tags->isNotEmpty())
                        <div class="mb-4 flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                                <span class="rounded-full border border-accent/10 bg-accent/10 px-3 py-1 text-xs text-accent-light">{{ $tag->tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    <h1 class="mb-4 text-3xl font-extrabold text-white md:text-4xl">{{ $post->title }}</h1>

                    <div class="mb-8 flex flex-wrap items-center gap-3 border-b border-white/[0.04] pb-8 text-sm text-gray-500">
                        @if($post->published_at)
                            <span>{{ $post->published_at->format('M d, Y') }}</span>
                        @else
                            <span class="text-amber-400">Draft</span>
                        @endif
                        <span>&middot;</span>
                        <span>{{ $post->reading_time_minutes ?? 1 }} min read</span>
                        <span>&middot;</span>
                        <span>{{ number_format($post->view_count ?? 0) }} views</span>
                    </div>

                    {{-- Mobile table of contents --}}
                    @if(count($toc))
                        <details class="mb-8 rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4 lg:hidden">
                            <summary class="cursor-pointer text-sm font-semibold text-white">Table of Contents</summary>
                            <nav class="mt-3 space-y-1.5">
                                @foreach($toc as $item)
                                    <a href="#{{ $item['id'] }}"
                                       class="block text-sm text-gray-400 hover:text-accent {{ ($item['level'] ?? 2) === 3 ? 'pl-4' : '' }}">
                                        {{ $item['text'] }}
                                    </a>
                                @endforeach
                            </nav>
                        </details>
                    @endif

                    {{-- Body (rendered + sanitized HTML) --}}
                    <div class="article-prose" x-ref="content">
                        {!! $post->content_html !!}
                    </div>

                    <div class="mt-12 border-t border-white/[0.04] pt-8">
                        <a href="{{ route('blogs.index') }}"
                           class="inline-flex items-center gap-2 text-sm font-medium text-accent transition-colors hover:text-accent-light">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to all articles
                        </a>
                    </div>
                </div>

                {{-- Sticky TOC sidebar (desktop) --}}
                @if(count($toc))
                    <aside class="hidden lg:block">
                        <div class="sticky top-28">
                            <p class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h7"/></svg>
                                On this page
                            </p>
                            <nav class="space-y-1 border-l border-white/[0.06]">
                                @foreach($toc as $item)
                                    <a href="#{{ $item['id'] }}"
                                       @click="active = '{{ $item['id'] }}'"
                                       class="-ml-px block border-l-2 py-1 pl-4 text-sm transition-colors {{ ($item['level'] ?? 2) === 3 ? 'pl-7 text-[0.8rem]' : '' }}"
                                       :class="active === '{{ $item['id'] }}' ? 'border-accent text-accent-light' : 'border-transparent text-gray-500 hover:text-gray-300'">
                                        {{ $item['text'] }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>
                    </aside>
                @endif

            </div>
        </article>
    </div>

    <x-public.footer />

    <x-public.prose-styles />

    <script>
        function articleReader() {
            return {
                progress: 0,
                active: '',
                init() {
                    this.highlight();
                    this.addCopyButtons();
                    this.observeHeadings();
                    this.onScroll();
                    window.addEventListener('scroll', () => this.onScroll(), { passive: true });
                    window.addEventListener('resize', () => this.onScroll(), { passive: true });
                },
                onScroll() {
                    const el = this.$refs.content;
                    if (!el) return;
                    const start = el.offsetTop;
                    const total = el.offsetHeight;
                    const seen = window.scrollY + window.innerHeight - start;
                    this.progress = Math.min(100, Math.max(0, (seen / total) * 100));
                },
                highlight() {
                    if (!window.hljs || !this.$refs.content) return;
                    this.$refs.content.querySelectorAll('pre code').forEach((block) => {
                        window.hljs.highlightElement(block);
                    });
                },
                addCopyButtons() {
                    if (!this.$refs.content) return;
                    this.$refs.content.querySelectorAll('pre').forEach((pre) => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'code-copy-btn';
                        btn.textContent = 'Copy';
                        btn.addEventListener('click', () => {
                            const code = pre.querySelector('code');
                            navigator.clipboard.writeText(code ? code.innerText : pre.innerText);
                            btn.textContent = 'Copied!';
                            setTimeout(() => { btn.textContent = 'Copy'; }, 1500);
                        });
                        pre.appendChild(btn);
                    });
                },
                observeHeadings() {
                    if (!this.$refs.content) return;
                    const headings = this.$refs.content.querySelectorAll('h2, h3');
                    if (!headings.length) return;
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) this.active = entry.target.id;
                        });
                    }, { rootMargin: '-100px 0px -65% 0px', threshold: 0 });
                    headings.forEach((h) => observer.observe(h));
                },
            };
        }
    </script>
</x-layouts.app>
