@props(['project'])

@php
    use Illuminate\Support\Str;

    $toUrl = fn ($p) => $p ? (Str::startsWith($p, 'http') ? $p : asset('storage/'.$p)) : null;

    $cover = $toUrl($project->cover_image);

    // Carousel images: cover first, then gallery (in sort order).
    $images = collect();
    if ($cover) {
        $images->push($cover);
    }
    foreach ($project->images as $img) {
        if ($url = $toUrl($img->image_path)) {
            $images->push($url);
        }
    }

    $payload = [
        'title' => $project->title,
        'description' => $project->short_description,
        'tech' => $project->tech_stack ?? [],
        'demo' => $project->demo_url,
        'images' => $images->values()->all(),
    ];
@endphp

<div role="button" tabindex="0"
     @click="$dispatch('open-project', @js($payload))"
     @keydown.enter.prevent="$dispatch('open-project', @js($payload))"
     @keydown.space.prevent="$dispatch('open-project', @js($payload))"
     class="group relative flex h-full cursor-pointer flex-col overflow-hidden rounded-2xl border border-white/[0.04] bg-dark-800 transition-all duration-300 hover:border-accent/20 focus:outline-none focus:ring-2 focus:ring-accent/40">
    <div class="relative overflow-hidden">
        @if($cover)
            <img src="{{ $cover }}" alt="{{ $project->title }}" loading="lazy"
                 class="h-48 w-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="flex h-48 w-full items-center justify-center bg-dark-700">
                <span class="text-4xl font-extrabold text-accent/20">{{ strtoupper(substr($project->title, 0, 1)) }}</span>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-dark-800 to-transparent"></div>
    </div>

    <div class="flex flex-1 flex-col p-6">
        <h3 class="mb-2 text-xl font-bold text-white transition-colors group-hover:text-accent">{{ $project->title }}</h3>
        <p class="mb-4 line-clamp-2 flex-1 text-sm text-gray-400">{{ $project->short_description }}</p>

        @if(!empty($project->tech_stack))
            <div class="flex flex-wrap gap-2">
                @foreach($project->tech_stack as $tech)
                    <span class="rounded-full bg-accent/10 px-2 py-1 text-xs text-accent-light">{{ $tech }}</span>
                @endforeach
            </div>
        @endif
    </div>
</div>
