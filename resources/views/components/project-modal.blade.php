{{--
    Public project showcase modal — image-on-top gallery.
    Opened by project cards via:
        $dispatch('open-project', { title, description, tech:[], demo, images:[] })
    Alpine only (no Livewire). Include once per page that lists projects.
--}}
<div
    x-data="{
        open: false,
        project: null,
        slide: 0,
        timer: null,
        show(detail) {
            this.project = detail;
            this.slide = 0;
            this.open = true;
            document.body.style.overflow = 'hidden';
            this.start();
        },
        close() {
            this.open = false;
            document.body.style.overflow = '';
            this.stop();
        },
        count() { return this.project ? this.project.images.length : 0 },
        go(i) { if (this.count()) { this.slide = (i + this.count()) % this.count(); this.start() } },
        next() { if (this.count() > 1) this.slide = (this.slide + 1) % this.count() },
        prev() { if (this.count() > 1) this.slide = (this.slide - 1 + this.count()) % this.count() },
        start() { this.stop(); if (this.count() > 1) this.timer = setInterval(() => this.next(), 2000) },
        stop() { if (this.timer) { clearInterval(this.timer); this.timer = null } },
    }"
    @open-project.window="show($event.detail)"
    @keydown.escape.window="if (open) close()"
    @keydown.arrow-right.window="if (open) { next(); start() }"
    @keydown.arrow-left.window="if (open) { prev(); start() }"
    x-cloak
>
    <div x-show="open" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display:none">
        {{-- Backdrop --}}
        <div x-show="open" x-transition.opacity.duration.200ms
             class="absolute inset-0 bg-black/80 backdrop-blur-sm"
             @click="close()"></div>

        {{-- Dialog --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-6 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             class="relative z-10 flex max-h-[92vh] w-full max-w-6xl flex-col overflow-hidden rounded-2xl border border-white/[0.06] bg-dark-900 shadow-2xl shadow-black/60">

            {{-- Close (floating, top-right) --}}
            <button type="button" @click="close()"
                    class="absolute right-4 top-4 z-30 flex h-10 w-10 items-center justify-center rounded-full border border-white/[0.08] bg-dark-950/70 text-gray-300 backdrop-blur-sm transition-colors hover:bg-dark-950 hover:text-white"
                    aria-label="Close">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- ===== Hero image (big, on top) ===== --}}
            <div class="group relative w-full shrink-0 overflow-hidden bg-dark-950 h-[42vh] sm:h-[52vh] md:h-[58vh]"
                 @mouseenter="stop()" @mouseleave="start()">
                <template x-for="(img, i) in (project ? project.images : [])" :key="i">
                    <img :src="img" :alt="project.title"
                         x-show="slide === i"
                         x-transition:enter="transition-opacity duration-500 ease-out"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="absolute inset-0 h-full w-full object-cover">
                </template>

                <template x-if="project && !project.images.length">
                    <div class="flex h-full w-full items-center justify-center text-sm text-gray-600">No images for this project.</div>
                </template>

                {{-- subtle top vignette so the close button stays legible --}}
                <div class="pointer-events-none absolute inset-x-0 top-0 h-20 bg-gradient-to-b from-black/40 to-transparent"></div>

                {{-- Hover prev/next chevrons --}}
                <template x-if="project && project.images.length > 1">
                    <div>
                        <button type="button" @click="prev(); start()"
                                class="absolute left-4 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/[0.1] bg-dark-950/60 text-white opacity-0 backdrop-blur-sm transition-opacity duration-200 hover:bg-dark-950 group-hover:opacity-100"
                                aria-label="Previous image">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" @click="next(); start()"
                                class="absolute right-4 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/[0.1] bg-dark-950/60 text-white opacity-0 backdrop-blur-sm transition-opacity duration-200 hover:bg-dark-950 group-hover:opacity-100"
                                aria-label="Next image">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- ‹ n / total › pill --}}
                        <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 items-center gap-2 rounded-full border border-white/[0.08] bg-black/60 px-2 py-1 backdrop-blur-sm">
                            <button type="button" @click="prev(); start()" class="flex h-6 w-6 items-center justify-center rounded-full text-white/80 hover:bg-white/10 hover:text-white" aria-label="Previous">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <span class="min-w-[42px] text-center text-xs font-medium tabular-nums text-white" x-text="(slide + 1) + ' / ' + count()"></span>
                            <button type="button" @click="next(); start()" class="flex h-6 w-6 items-center justify-center rounded-full text-white/80 hover:bg-white/10 hover:text-white" aria-label="Next">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            {{-- ===== Thumbnail strip ===== --}}
            <template x-if="project && project.images.length > 1">
                <div class="flex shrink-0 gap-2 overflow-x-auto border-t border-white/[0.04] bg-dark-950/40 px-4 py-3">
                    <template x-for="(img, i) in project.images" :key="i">
                        <button type="button" @click="go(i)"
                                class="relative h-14 w-20 shrink-0 overflow-hidden rounded-md border transition-all duration-200"
                                :class="slide === i ? 'border-transparent ring-2 ring-accent' : 'border-white/[0.06] opacity-60 hover:opacity-100'">
                            <img :src="img" alt="" class="h-full w-full object-cover">
                        </button>
                    </template>
                </div>
            </template>

            {{-- ===== Details ===== --}}
            <div class="overflow-y-auto p-6 sm:p-8">
                <h3 class="mb-3 text-2xl font-extrabold text-white sm:text-3xl" x-text="project?.title"></h3>
                <p class="mb-6 leading-relaxed text-gray-400" x-text="project?.description"></p>

                <template x-if="project && project.tech.length">
                    <div class="mb-7 flex flex-wrap gap-2">
                        <template x-for="t in project.tech" :key="t">
                            <span class="rounded-full border border-accent/10 bg-accent/10 px-3 py-1.5 text-sm text-accent-light" x-text="t"></span>
                        </template>
                    </div>
                </template>

                <template x-if="project && project.demo">
                    <a :href="project.demo" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-xl bg-accent px-6 py-3 text-sm font-extrabold uppercase tracking-widest text-black transition-all duration-300 hover:bg-accent-light">
                        Live Demo
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </template>
            </div>
        </div>
    </div>
</div>
