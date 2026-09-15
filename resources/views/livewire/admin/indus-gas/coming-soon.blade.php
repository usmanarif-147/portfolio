<div>
    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
        <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-gray-300 transition-colors">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span>Indus Gas</span>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-gray-300">{{ $feature }}</span>
    </div>

    <div class="bg-dark-800 border border-dark-700 rounded-xl p-8 sm:p-12 text-center">
        <span class="mx-auto w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center">
            <svg class="w-7 h-7 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m4-2a8 8 0 11-16 0 8 8 0 0116 0z" />
            </svg>
        </span>
        <h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider mt-5">{{ $feature }}</h1>
        <p class="text-gray-500 mt-2">This Indus Gas feature is coming soon.</p>
    </div>
</div>
