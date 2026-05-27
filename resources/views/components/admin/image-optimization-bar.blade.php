@props([
    'original' => 0,   // total original bytes
    'compressed' => 0, // total compressed bytes
    'label' => null,   // optional context label, e.g. "3 images"
])

@php
    $original = (int) $original;
    $compressed = (int) $compressed;
    $saved = max(0, $original - $compressed);
    $pct = $original > 0 ? (int) round($saved / $original * 100) : 0;

    // Self-contained byte formatter (no intl/Number dependency).
    $fmt = function (int $bytes): string {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    };
@endphp

@if ($original > 0)
    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 rounded-lg border border-dark-600 bg-dark-700/50 px-3 py-2 text-xs">
        <span class="font-medium text-gray-400">
            @if ($label) {{ $label }} · @endif Size
        </span>
        <span class="text-gray-400">Original <span class="font-mono text-gray-300">{{ $fmt($original) }}</span></span>
        <svg class="h-3 w-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        <span class="text-gray-400">Optimized <span class="font-mono text-primary-light">{{ $fmt($compressed) }}</span></span>
        @if ($pct > 0)
            <span class="rounded-full bg-success/10 px-2 py-0.5 font-medium text-success">&minus;{{ $pct }}%</span>
        @endif
    </div>
@endif
