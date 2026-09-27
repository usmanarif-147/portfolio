@props(['group', 'selection', 'multiple' => false, 'items', 'label', 'help' => null, 'variant' => 'pill'])

<div>
    <div class="flex items-baseline justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-gray-200">{{ $label }}</p>
            @if ($help)
                <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
            @endif
        </div>
        <button type="button" @click="openTagModal('{{ $group }}')" class="text-xs font-medium text-primary-light hover:text-white">
            + Add tag
        </button>
    </div>

    <div class="mt-3 flex flex-wrap gap-2">
        <template x-for="tag in {{ $items }}" :key="tag.id">
            <div class="group relative">
                <button type="button" @click="toggleTag('{{ $selection }}', {{ $multiple ? 'true' : 'false' }}, tag.id)"
                    :class="isTagSelected('{{ $selection }}', {{ $multiple ? 'true' : 'false' }}, tag.id) ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'"
                    class="{{ $variant === 'card' ? 'rounded-lg py-3' : 'rounded-full py-2' }} border px-4 pr-16 text-left text-sm font-medium transition-colors">
                    <span x-text="tag.name"></span>
                </button>
                <span class="absolute inset-y-0 right-2 flex items-center gap-1 text-gray-500 transition-colors group-hover:text-gray-200 focus-within:text-gray-200">
                    <button type="button" @click.stop="openTagModal('{{ $group }}', tag)" class="rounded p-1 text-gray-400 hover:bg-dark-600 hover:text-white" aria-label="Edit tag">✎</button>
                    <button type="button" @click.stop="removeTag('{{ $group }}', tag.id)" class="rounded p-1 text-gray-400 hover:bg-danger/20 hover:text-danger" aria-label="Remove tag">×</button>
                </span>
            </div>
        </template>
    </div>
</div>
