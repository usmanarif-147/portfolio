<div x-show="tagModal.open" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4" @keydown.escape.window="closeTagModal()">
    <div class="w-full max-w-sm rounded-xl border border-dark-600 bg-dark-800 p-6 shadow-2xl" @click.outside="closeTagModal()">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-mono font-semibold text-white" x-text="tagModal.id ? 'Edit tag' : 'Add new tag'"></h3>
                <p class="mt-1 text-sm text-gray-500" x-text="tagModal.group === 'expenseTypes' ? 'Add a new expense type for this form.' : 'Add a new person for this form.'"></p>
            </div>
            <button type="button" @click="closeTagModal()" class="text-xl leading-none text-gray-500 hover:text-white" aria-label="Close">×</button>
        </div>
        <label for="expense-tag-name" class="mt-5 block text-sm font-medium text-gray-200">Tag name</label>
        <input id="expense-tag-name" type="text" x-model="tagModal.name" @keydown.enter.prevent="saveTag()" x-ref="tagName"
            class="mt-2 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20"
            placeholder="Enter a name">
        <p x-show="tagModal.error" x-text="tagModal.error" class="mt-2 text-xs text-danger"></p>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" @click="closeTagModal()" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-400 hover:bg-dark-700 hover:text-white">Cancel</button>
            <button type="button" @click="saveTag()" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary-hover">Save tag</button>
        </div>
    </div>
</div>
