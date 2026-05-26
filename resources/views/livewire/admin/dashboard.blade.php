<div>
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider">Dashboard</h1>
        <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}.</p>
    </div>

    {{-- Coming soon --}}
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-12 flex flex-col items-center justify-center text-center">
        <span class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5">
            <svg class="w-7 h-7 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </span>
        <h2 class="text-xl font-mono font-bold text-white uppercase tracking-wider">Coming Soon</h2>
        <p class="text-gray-500 mt-2 max-w-md">This dashboard is being rebuilt. Check back later.</p>
    </div>
</div>
