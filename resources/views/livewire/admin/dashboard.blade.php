<div>
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider">Dashboard</h1>
        <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        {{-- Total Projects --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 flex items-center gap-5">
            <span class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-mono text-gray-500 uppercase tracking-wider">Total Projects</p>
                <p class="text-3xl font-bold text-white mt-1">{{ number_format($projectsCount) }}</p>
            </div>
        </div>

        {{-- Visitors --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 flex items-center gap-5">
            <span class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-mono text-gray-500 uppercase tracking-wider">Visitors</p>
                <p class="text-3xl font-bold text-white mt-1">{{ number_format($visitorsCount) }}</p>
            </div>
        </div>

        {{-- Resume Downloads --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 flex items-center gap-5">
            <span class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-mono text-gray-500 uppercase tracking-wider">Resume Downloads</p>
                <p class="text-3xl font-bold text-white mt-1">{{ number_format($resumeDownloadsCount) }}</p>
            </div>
        </div>
    </div>
</div>
