<div>
    @include('resume.templates._styles')

    {{-- ============ PAGE CHROME ============ --}}
    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
        <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-gray-300 transition-colors">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-300">Resume Builder</span>
    </div>

    <div class="flex items-start justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider">Resume Builder</h1>
            <p class="text-gray-500 mt-1">
                Live preview built from your portfolio data. Toggle <span class="text-primary-light">Include in resume</span>
                on up to 3 <a href="{{ route('admin.projects.index') }}" wire:navigate class="text-primary-light hover:underline">projects</a>
                and 3 <a href="{{ route('admin.experiences.index') }}" wire:navigate class="text-primary-light hover:underline">experiences</a>
                to control what appears below.
            </p>
        </div>
        <button type="button" wire:click="downloadPdf" wire:loading.attr="disabled"
                class="bg-primary hover:bg-primary-hover text-white font-medium rounded-lg px-5 py-2.5 transition-colors text-sm flex items-center gap-2 shrink-0 disabled:opacity-60">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            <span wire:loading.remove wire:target="downloadPdf">Download PDF</span>
            <span wire:loading wire:target="downloadPdf">Generating…</span>
        </button>
    </div>

    {{-- ============ LIVE PREVIEW (same partial used by PDF) ============ --}}
    @include('resume.templates._body', ['interactive' => false])
</div>
