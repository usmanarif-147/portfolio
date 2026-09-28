<div>
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('success') }}</div>
    @endif

    <div class="rounded-xl border border-dark-700 bg-dark-800 p-4">
        <div class="flex items-start justify-between gap-4">
            <div><p class="font-medium text-gray-200">Saved categories</p><p class="text-xs text-gray-500">Categories control the expense form shown to your staff.</p></div>
            <button type="button" wire:click="create" class="rounded-lg bg-primary px-3 py-2 text-sm font-medium text-white">+ Add category</button>
        </div>
        <div class="mt-4 flex flex-wrap gap-2">
            @forelse($categories as $category)
                <button type="button" wire:click="edit({{ $category->id }})" class="rounded-full border border-dark-600 px-3 py-1.5 text-sm {{ $category->is_active ? 'text-gray-300' : 'text-gray-500 line-through' }}">{{ $category->name }} ({{ $category->types->count() }})</button>
            @empty
                <p class="text-sm text-gray-500">No saved categories yet.</p>
            @endforelse
        </div>
    </div>

    @if($open)
        <div class="fixed inset-0 z-[70] overflow-y-auto bg-black/75 p-4">
            <div class="flex min-h-full items-center justify-center py-4">
                <form wire:submit="save" class="w-full max-w-3xl rounded-xl border border-dark-600 bg-dark-800 p-6 shadow-2xl">
                    <div class="flex items-start justify-between gap-4">
                        <div><p class="text-xs font-semibold uppercase tracking-wider text-primary-light">Expense setup</p><h2 class="mt-1 font-mono text-xl text-white">{{ $categoryId ? 'Edit category' : 'Add category' }}</h2><p class="mt-1 text-sm text-gray-500">Choose what this category needs. Its expense form will be built from these settings.</p></div>
                        <button type="button" wire:click="$set('open', false)" class="text-2xl text-gray-400">×</button>
                    </div>

                    <div class="mt-6 space-y-6">
                        <div><label class="text-sm text-gray-300">Category title</label><input wire:model="name" placeholder="For example: LPG" class="mt-1 w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-white">@error('name')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror</div>

                        <div><div class="flex items-center justify-between"><label class="text-sm text-gray-300">Expense types</label><button type="button" wire:click="addType" class="text-xs font-medium text-primary-light">+ Add type</button></div><p class="mt-1 text-xs text-gray-500">For example: Filling Charges, Filling Cost, Rent.</p>
                            @foreach($expenseTypes as $index => $type)
                                <div wire:key="expense-type-{{ $index }}" class="mt-2 flex gap-2"><input wire:model="expenseTypes.{{ $index }}" placeholder="Expense type" class="w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2 text-white"><button type="button" wire:click="removeType({{ $index }})" class="rounded-lg px-3 text-danger hover:bg-danger/10">×</button></div>
                            @endforeach
                            @error('expenseTypes.0')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="text-sm text-gray-300">Expense types display<select wire:model="expense_type_display" class="mt-1 w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2 text-white"><option value="tags">Clickable tags</option><option value="dropdown">Dropdown</option></select></label>
                            <label class="text-sm text-gray-300">Staff display<select wire:model="staff_display" class="mt-1 w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2 text-white"><option value="tags">Clickable tags</option><option value="dropdown">Dropdown</option></select></label>
                        </div>

                        <div><label class="text-sm text-gray-300">How should staff be used?</label><div class="mt-2 grid gap-3 sm:grid-cols-2"><label class="rounded-lg border border-dark-600 bg-dark-900 p-3 text-sm text-gray-300"><input type="radio" wire:model="staff_selection_mode" value="multiple_payers" class="mr-2">Who paid? <span class="block pl-5 text-xs text-gray-500">Select one or more people and enter each paid amount.</span></label><label class="rounded-lg border border-dark-600 bg-dark-900 p-3 text-sm text-gray-300"><input type="radio" wire:model="staff_selection_mode" value="single_responsible" class="mr-2">Who was responsible? <span class="block pl-5 text-xs text-gray-500">Select one person and enter one total amount.</span></label></div></div>

                        <div><label class="text-sm text-gray-300">Allowed staff</label><p class="mt-1 text-xs text-gray-500">Only selected staff will appear while adding this expense.</p><div class="mt-3 flex flex-wrap gap-3">@forelse($staff as $member)<label class="flex items-center gap-2 text-sm text-gray-300"><input type="checkbox" wire:model="staffIds" value="{{ $member->id }}">{{ $member->name }}</label>@empty<p class="text-sm text-gray-500">Create staff members first, then return here.</p>@endforelse</div></div>

                        <div class="rounded-lg border border-dark-600 bg-dark-900/60 p-4"><p class="text-sm font-medium text-gray-200">Extra fields for this category</p><label class="mt-3 flex items-center gap-2 text-sm text-gray-300"><input type="checkbox" wire:model="requires_cylinder_quantity">Show “Total number of cylinders”</label><p class="mt-1 pl-5 text-xs text-gray-500">Enable this for LPG. Bike and Car fuel fields remain their separate default forms.</p></div>
                        <label class="flex items-center gap-2 text-sm text-gray-300"><input type="checkbox" wire:model="is_active">Active category</label>
                    </div>

                    <div class="mt-7 flex justify-end gap-3"><button type="button" wire:click="$set('open', false)" class="rounded-lg px-4 py-2 text-sm text-gray-400">Cancel</button><button class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white">Save category</button></div>
                </form>
            </div>
        </div>
    @endif
</div>
