<div>
    <div class="flex items-end justify-between">
        <div>
            <h1 class="font-mono text-2xl font-bold text-white">EXPENSES</h1>
            <p class="text-sm text-gray-500">Save every business expense.</p>
        </div><button type="button" wire:click="openForm" class="rounded-lg bg-primary px-4 py-2.5 text-white">+ Add
            expense</button>
    </div>
    @if (session('success') || $notice)
        <div class="mt-4 rounded-lg bg-emerald-500/10 p-3 text-sm text-emerald-300">{{ $notice ?? session('success') }}
        </div>
    @endif
    <div class="mt-6 overflow-hidden rounded-xl border border-dark-700 bg-dark-800">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-dark-900 text-gray-500">
                <tr>
                    <th class="p-4">Date</th>
                    <th>Amount</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dailyExpenses as $day)
                    <tr class="border-t border-dark-700 text-gray-300">
                        <td class="p-4">
                            {{ \Carbon\Carbon::parse($day->expense_date, 'Asia/Karachi')->format('d M Y') }}</td>
                        <td>PKR {{ number_format($day->total_amount, 2) }} <span
                                class="ml-2 text-xs text-gray-500">({{ $day->expense_count }} expenses)</span></td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.indus-gas.expenses.payers', ['expense' => $day->expense_id]) }}"
                                    class="rounded-lg border border-dark-600 px-3 py-2 text-xs text-gray-300 hover:border-primary hover:text-primary-light">Payers</a>
                                <a href="{{ route('admin.indus-gas.expenses.day', ['expense' => $day->expense_id]) }}"
                                    class="rounded-lg border border-primary px-3 py-2 text-xs text-primary-light hover:bg-primary/10">View details</a>
                            </div>
                        </td>
                </tr>@empty<tr>
                        <td colspan="3" class="p-10 text-center text-gray-500">No expenses saved yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <livewire:admin.indus-gas.expenses.category-manager />
    @if ($showForm)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-black/75 p-4">
            <div class="flex min-h-full items-center justify-center">
                <form wire:submit="save"
                    class="w-full max-w-3xl rounded-2xl border border-dark-600 bg-dark-800 shadow-2xl">
                    <div class="flex items-start justify-between border-b border-dark-700 px-6 py-5">
                        <div>
                            <h2 class="font-mono text-xl text-white">Add expense</h2>
                            <p class="mt-1 text-sm text-gray-500">Choose a category and complete its fields.</p>
                        </div><button type="button" wire:click="closeForm"
                            class="rounded p-1 text-xl text-gray-400 hover:bg-dark-700 hover:text-white">×</button>
                    </div>
                    <div class="space-y-6 p-6">
                        <div>
                            <div class="flex items-center justify-between"><label
                                    class="font-medium text-gray-200">Expense category</label><button type="button"
                                    wire:click="openCategoryManager"
                                    class="rounded px-2 py-1 text-xs font-medium text-primary-light hover:bg-primary/10">+
                                    Add category</button></div>
                            <p class="mt-1 text-xs text-gray-500">Choose a category or use its pencil button to edit its
                                setup.</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @forelse($categories as $c)
                                    <div
                                        class="inline-flex overflow-hidden rounded-lg border {{ $expenseCategoryId === $c->id ? 'border-primary' : 'border-dark-600' }}">
                                        <button type="button" wire:click="selectCategory({{ $c->id }})"
                                            class="bg-dark-900 px-3 py-2 text-sm {{ $expenseCategoryId === $c->id ? 'text-primary-light' : 'text-gray-300' }} hover:bg-dark-700">{{ $c->name }}</button><button
                                            type="button" wire:click.stop="openCategoryManager({{ $c->id }})"
                                            title="Edit {{ $c->name }} category"
                                            aria-label="Edit {{ $c->name }} category"
                                            class="border-l border-dark-600 bg-dark-800 px-2 text-sm text-gray-400 hover:bg-primary/10 hover:text-primary-light">✎</button>
                                </div>@empty<p class="text-sm text-gray-500">No categories yet. Use Add category to
                                        create one.</p>
                                @endforelse
                            </div>
                            @error('expenseCategoryId')
                                <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        @if ($selectedCategory)
                            <div class="space-y-5 border-t border-dark-700 pt-6">
                                <div><label class="font-medium text-gray-200">Expense type</label>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach ($selectedCategory->types as $t)
                                            <button type="button"
                                                wire:click="$set('expenseTypeId',{{ $t->id }})"
                                                class="rounded-lg border px-3 py-2 text-sm {{ $expenseTypeId === $t->id ? 'border-primary bg-primary/10 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-300' }}">{{ $t->name }}</button>
                                        @endforeach
                                    </div>
                                </div>
                                @foreach ($selectedCategory->form_fields ?? [] as $f)
                                    <div><label class="text-sm text-gray-200">{{ $f['label'] }} @if ($f['required'])
                                                *
                                            @endif
                                        </label>
                                        <input type="{{ $f['type'] === 'text' ? 'text' : 'number' }}"
                                            @if ($f['type'] === 'decimal') step="0.01" @endif
                                            wire:model="formData.{{ $f['key'] }}"
                                            placeholder="{{ $f['placeholder'] }}"
                                            class="mt-2 w-full rounded-lg border border-dark-600 bg-dark-900 p-2.5 text-white">
                                        @error('formData.' . $f['key'])
                                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endforeach @php($driver = in_array($selectedCategory->slug, ['bike', 'car'])) @if ($driver)
                                        <div><label class="font-medium text-gray-200">Who was the driver?</label>
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach (\App\Models\IndusGas\Staff::orderBy('name')->get() as $s)
                                                    <button type="button"
                                                        wire:click="$set('responsibleStaffId',{{ $s->id }})"
                                                        class="rounded-full border px-3 py-2 text-sm {{ $responsibleStaffId === $s->id ? 'border-primary bg-primary/10 text-primary-light' : 'border-dark-600 text-gray-300' }}">{{ $s->name }}</button>
                                                @endforeach
                                            </div>
                                    </div>@else<div><label class="font-medium text-gray-200">Who paid?</label>
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach ($selectedCategory->staff as $s)
                                                    <button type="button"
                                                        wire:click="togglePayer({{ $s->id }})"
                                                        class="rounded-full border px-3 py-2 text-sm {{ in_array($s->id, $selectedStaffIds, true) ? 'border-primary bg-primary/10 text-primary-light' : 'border-dark-600 text-gray-300' }}">{{ $s->name }}</button>
                                                @endforeach
                                            </div>
                                            @foreach ($selectedCategory->staff->whereIn('id', $selectedStaffIds) as $s)
                                                <div class="mt-3"><label
                                                        class="text-sm text-gray-300">{{ $s->name }} paid
                                                        (PKR)</label><input type="number" step="0.01"
                                                        wire:model="payerAmounts.{{ $s->id }}"
                                                        class="mt-1 w-full rounded-lg border border-dark-600 bg-dark-900 p-2 text-white">
                                                </div>
                                            @endforeach @error('payerAmounts')
                                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                                <div>
                                    <label class="text-sm text-gray-200">Expense date</label><input type="date"
                                        wire:model="expenseDate"
                                        class="mt-2 rounded-lg border border-dark-600 bg-dark-900 p-2 text-white">
                                </div>
                        </div>
                    @else
                        <div
                            class="rounded-lg border border-dashed border-dark-600 p-6 text-center text-sm text-gray-500">
                            Select a category to continue.</div>
                    @endif
                </div>
                @if ($selectedCategory)
                    <div class="flex justify-end border-t border-dark-700 px-6 py-4"><button
                            class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white">Save
                            expense</button></div>
                @endif
            </form>
        </div>
    </div>
@endif
</div>
