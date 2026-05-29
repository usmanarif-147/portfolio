<div>
    @php
        $flag = fn (?string $cc) => $cc ? collect(str_split(strtoupper($cc)))->map(fn ($c) => mb_chr(127397 + ord($c)))->implode('') : '';
        $hostname = fn (?string $url) => $url ? (parse_url($url, PHP_URL_HOST) ?? 'Direct') : 'Direct';
    @endphp

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
        <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-gray-300 transition-colors">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-300">Analytics</span>
    </div>

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider">Analytics</h1>
            <p class="text-gray-500 mt-1">Recent visitors and resume downloads.</p>
        </div>
    </div>

    {{-- Tab switcher --}}
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-2 mb-4 inline-flex gap-1">
        <button type="button" wire:click="$set('tab', 'visitors')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $tab === 'visitors' ? 'bg-primary/10 text-primary-light' : 'text-gray-400 hover:text-white' }}">
            Visitors
        </button>
        <button type="button" wire:click="$set('tab', 'downloads')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $tab === 'downloads' ? 'bg-primary/10 text-primary-light' : 'text-gray-400 hover:text-white' }}">
            Resume Downloads
        </button>
    </div>

    {{-- Period filter pills --}}
    <div class="flex flex-wrap items-center gap-2 mb-6">
        @php
            $periods = [
                'today' => 'Today',
                'week' => 'This Week',
                'month' => 'This Month',
                'all' => 'All Time',
            ];
        @endphp
        @foreach ($periods as $key => $label)
            <button type="button" wire:click="$set('period', '{{ $key }}')"
                    class="px-3 py-1.5 rounded-full text-xs font-medium transition-colors {{ $period === $key ? 'bg-primary text-white' : 'bg-dark-800 border border-dark-700 text-gray-400 hover:text-white' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php
            $cards = [
                'today' => 'Today',
                'week' => 'This Week',
                'month' => 'This Month',
                'all' => 'All Time',
            ];
        @endphp
        @foreach ($cards as $key => $label)
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <p class="text-xs font-mono font-medium text-gray-500 uppercase tracking-wider">{{ $label }}</p>
                <p class="text-3xl font-bold text-white mt-2">{{ number_format($counts[$key] ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $tab === 'downloads' ? 'downloads' : 'visitors' }}
                </p>
            </div>
        @endforeach
    </div>

    {{-- Top N panels --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Top Countries --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h2 class="text-sm font-mono font-bold text-white uppercase tracking-wider mb-4">Top Countries</h2>
            @if ($topCountries->isEmpty())
                <p class="text-sm text-gray-500">No data.</p>
            @else
                <ul class="space-y-2.5">
                    @foreach ($topCountries as $row)
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-gray-300 flex items-center gap-2">
                                <span>{{ $flag($row->country_code) }}</span>
                                <span>{{ $row->country }}</span>
                            </span>
                            <span class="text-gray-500 font-mono">{{ number_format($row->total) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Top Referrers --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h2 class="text-sm font-mono font-bold text-white uppercase tracking-wider mb-4">Top Referrers</h2>
            @if ($topReferrers->isEmpty())
                <p class="text-sm text-gray-500">No data.</p>
            @else
                <ul class="space-y-2.5">
                    @foreach ($topReferrers as $row)
                        <li class="flex items-center justify-between text-sm gap-3">
                            <span class="text-gray-300 truncate">{{ $hostname($row->referrer) }}</span>
                            <span class="text-gray-500 font-mono shrink-0">{{ number_format($row->total) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Top Browsers --}}
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h2 class="text-sm font-mono font-bold text-white uppercase tracking-wider mb-4">Top Browsers</h2>
            @if ($topBrowsers->isEmpty())
                <p class="text-sm text-gray-500">No data.</p>
            @else
                <ul class="space-y-2.5">
                    @foreach ($topBrowsers as $row)
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-gray-300">{{ $row->browser }}</span>
                            <span class="text-gray-500 font-mono">{{ number_format($row->total) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Activity table --}}
    <div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-dark-700/50">
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">{{ $tab === 'downloads' ? 'Last Download' : 'Last Seen' }}</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Country</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">City</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Browser</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">OS</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Device</th>
                        @if ($tab === 'downloads')
                            <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Template</th>
                        @endif
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">{{ $tab === 'downloads' ? 'Downloads' : 'Visits' }}</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Source</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-700">
                    @forelse ($rows as $row)
                        <tr class="hover:bg-dark-700/30 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">{{ ($tab === 'downloads' ? $row->last_downloaded_at : $row->last_seen_at)->format('M d, Y g:i A') }}</td>
                            <td class="px-6 py-4 text-sm text-white whitespace-nowrap">
                                <span class="mr-1">{{ $flag($row->country_code) }}</span>{{ $row->country ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">{{ $row->city ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">{{ $row->browser ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">{{ $row->os ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full bg-dark-700 text-gray-300 text-xs">{{ $row->device_type ?? '—' }}</span>
                            </td>
                            @if ($tab === 'downloads')
                                <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">{{ $row->template ?? '—' }}</td>
                            @endif
                            <td class="px-6 py-4 text-sm text-gray-300 font-mono whitespace-nowrap">{{ number_format($tab === 'downloads' ? $row->download_count : $row->visit_count) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">{{ $hostname($row->referrer) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $tab === 'downloads' ? 9 : 8 }}" class="px-6 py-12 text-center text-gray-500">
                                No data yet — once visitors come, they'll appear here.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($rows->hasPages())
            <div class="px-6 py-4 border-t border-dark-700">
                {{ $rows->links() }}
            </div>
        @endif
    </div>
</div>
