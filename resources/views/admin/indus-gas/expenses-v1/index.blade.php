<x-layouts.admin>
    <div x-data="expensesV1()">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300">Dashboard</a><span>›</span><span>Indus Gas</span><span>›</span><span class="text-gray-300">Expenses v1</span>
                </div>
                <h1 class="mt-2 text-2xl font-mono font-bold uppercase tracking-wider text-white">Expenses v1</h1>
                <p class="mt-1 text-sm text-gray-500">A simple workspace for recording daily business expenses.</p>
            </div>
            <button type="button" @click="openCategories()" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-primary-hover">
                <span class="text-lg leading-none">+</span> Add expense
            </button>
        </div>

        <section class="mt-7 overflow-hidden rounded-xl border border-dark-700 bg-dark-800">
            <div class="flex items-center justify-between border-b border-dark-700 px-5 py-4">
                <div><h2 class="font-mono font-semibold text-white">Expense records</h2><p class="mt-1 text-xs text-gray-500">Expenses added during this browser session.</p></div>
                <span class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary-light" x-text="expenses.length + ' records'"></span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-dark-700 bg-dark-900/60 text-xs uppercase tracking-wider text-gray-500">
                        <tr><th class="px-5 py-3 font-medium">Date</th><th class="px-5 py-3 font-medium">Category</th><th class="px-5 py-3 font-medium">Expense type</th><th class="px-5 py-3 font-medium">Person</th><th class="px-5 py-3 text-right font-medium">Amount</th></tr>
                    </thead>
                    <tbody class="divide-y divide-dark-700">
                        <template x-for="expense in expenses" :key="expense.id">
                            <tr class="text-gray-300"><td class="whitespace-nowrap px-5 py-4" x-text="expense.date"></td><td class="px-5 py-4"><span class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary-light" x-text="expense.category"></span></td><td class="px-5 py-4" x-text="expense.type"></td><td class="px-5 py-4" x-text="expense.person"></td><td class="px-5 py-4 text-right font-mono text-white" x-text="formatPkr(expense.amount)"></td></tr>
                        </template>
                        <tr x-show="expenses.length === 0"><td colspan="5" class="px-5 py-14 text-center"><div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-dark-700 text-primary-light">+</div><p class="mt-3 font-medium text-gray-300">No expenses added yet</p><p class="mt-1 text-xs text-gray-500">Use “Add expense” to create your first entry.</p></td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <div x-show="modal.open" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/75 p-4 sm:p-8" @keydown.escape.window="closeModal()">
            <div class="flex min-h-full items-center justify-center">
                <div class="w-full max-w-5xl rounded-2xl border border-dark-600 bg-dark-800 shadow-2xl">
                    <template x-if="modal.step === 'categories'">
                        <div>
                            <div class="flex items-start justify-between border-b border-dark-700 px-6 py-5 sm:px-8">
                                <div><p class="text-xs font-semibold uppercase tracking-wider text-primary-light">Add expense</p><h2 class="mt-1 text-xl font-mono font-semibold text-white">Choose an expense category</h2><p class="mt-1 text-sm text-gray-500">Select where this expense belongs to continue.</p></div>
                                <button type="button" @click="closeModal()" class="text-2xl leading-none text-gray-500 hover:text-white" aria-label="Close">×</button>
                            </div>
                            <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3 sm:p-8">
                                <template x-for="category in categories" :key="category.id">
                                    <button type="button" @click="chooseCategory(category)" class="group rounded-xl border border-dark-700 bg-dark-900/50 p-5 text-left transition-colors hover:border-primary/60 hover:bg-primary/5">
                                        <div class="flex items-start justify-between gap-4"><span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-lg text-primary-light" x-text="category.icon"></span><span class="text-sm text-gray-600 transition-colors group-hover:text-primary-light">→</span></div>
                                        <h3 class="mt-5 font-mono font-semibold text-white" x-text="category.name"></h3><p class="mt-1 text-xs leading-5 text-gray-500" x-text="category.description"></p>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="modal.step === 'form'">
                        <form @submit.prevent="saveExpense()">
                            <div class="flex items-start justify-between border-b border-dark-700 px-6 py-5 sm:px-8">
                                <div class="flex items-start gap-3"><button type="button" @click="modal.step = 'categories'" class="mt-0.5 rounded-lg border border-dark-600 px-2 py-1 text-sm text-gray-400 hover:border-primary hover:text-white" aria-label="Back to categories">←</button><div><p class="text-xs font-semibold uppercase tracking-wider text-primary-light" x-text="form.category.name"></p><h2 class="mt-1 text-xl font-mono font-semibold text-white">Add <span x-text="form.category.name"></span> expense</h2><p class="mt-1 text-sm text-gray-500">Complete the fields below, then save the entry.</p></div></div>
                                <button type="button" @click="closeModal()" class="text-2xl leading-none text-gray-500 hover:text-white" aria-label="Close">×</button>
                            </div>

                            <div class="max-h-[70vh] space-y-7 overflow-y-auto p-6 sm:p-8">
                                <div>
                                    <div class="flex items-center justify-between gap-4"><label class="text-sm font-medium text-gray-200">Expense type</label><button type="button" @click="openTagEditor('types')" class="text-xs font-medium text-primary-light hover:text-white">+ Add type</button></div>
                                    <div class="mt-3 flex flex-wrap gap-2"><template x-for="type in form.types" :key="type.id"><div class="group relative"><button type="button" @click="form.type = type.id" :class="form.type === type.id ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'" class="rounded-lg border px-4 py-2.5 pr-14 text-sm font-medium"><span x-text="type.name"></span></button><span class="absolute inset-y-0 right-2 flex items-center gap-1"><button type="button" @click.stop="openTagEditor('types', type)" class="p-1 text-gray-500 hover:text-white">✎</button><button type="button" @click.stop="removeTag('types', type.id)" class="p-1 text-gray-500 hover:text-danger">×</button></span></div></template></div>
                                </div>

                                <div x-show="form.category.id === 'lpg'" x-cloak><label class="text-sm font-medium text-gray-200">Total number of cylinders</label><input type="number" min="1" x-model="form.cylinders" placeholder="For example: 50" class="mt-2 block w-full max-w-sm rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary"></div>

                                <div x-show="form.category.fuel" x-cloak class="space-y-5">
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3"><div><label class="text-xs text-gray-500">Distance travelled (km)</label><input type="number" min="0" x-model="form.distance" placeholder="10" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary"></div><div><label class="text-xs text-gray-500">Fuel average (km per litre)</label><input type="number" min="0" step="0.01" x-model="form.average" placeholder="8" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary"></div><div><label class="text-xs text-gray-500">Fuel price per litre (PKR)</label><input type="number" min="0" step="0.01" x-model="form.rate" placeholder="395.56" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary"></div></div>
                                    <div class="rounded-xl border border-primary/25 bg-primary/10 px-5 py-4"><div class="flex items-center justify-between gap-4"><span class="text-sm font-medium text-primary-light">Fuel cost</span><span class="font-mono text-lg font-semibold text-white" x-text="fuelCost() === null ? 'Enter trip details' : formatPkr(fuelCost())"></span></div><p class="mt-1 text-xs text-gray-400">(Distance travelled ÷ fuel average) × fuel price per litre</p></div>
                                </div>

                                <div class="border-t border-dark-700 pt-6">
                                    <div class="flex items-center justify-between gap-4"><div><label class="text-sm font-medium text-gray-200" x-text="form.category.fuel ? 'Who was the driver?' : 'Who paid?'"></label><p class="mt-1 text-xs text-gray-500" x-text="form.category.fuel ? 'Choose one' : 'Select one or more people'"></p></div><button type="button" @click="openTagEditor('people')" class="text-xs font-medium text-primary-light hover:text-white">+ Add person</button></div>
                                    <div class="mt-3 flex flex-wrap gap-2"><template x-for="person in form.people" :key="person.id"><div class="group relative"><button type="button" @click="selectPerson(person.id)" :class="personSelected(person.id) ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'" class="rounded-full border px-4 py-2 pr-14 text-sm font-medium"><span x-text="person.name"></span></button><span class="absolute inset-y-0 right-2 flex items-center gap-1"><button type="button" @click.stop="openTagEditor('people', person)" class="p-1 text-gray-500 hover:text-white">✎</button><button type="button" @click.stop="removeTag('people', person.id)" class="p-1 text-gray-500 hover:text-danger">×</button></span></div></template></div>
                                    <div x-show="!form.category.fuel" class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2"><template x-for="person in form.people.filter(person => form.payers.includes(person.id))" :key="person.id"><div><label class="text-sm font-medium text-gray-200"><span x-text="person.name"></span> paid</label><div class="relative mt-2"><span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-500">PKR</span><input type="number" min="0" x-model="form.amounts[person.id]" placeholder="0" class="block w-full rounded-lg border border-dark-600 bg-dark-900 py-2.5 pl-12 pr-3 text-sm text-white outline-none focus:border-primary"></div></div></template></div>
                                </div>

                                <div class="border-t border-dark-700 pt-6"><div class="flex items-center justify-between gap-4"><label class="text-sm font-medium text-gray-200">Expense date</label><span class="text-xs text-gray-500">Enter each part separately</span></div><div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4"><div><label class="text-xs text-gray-500">Date</label><input type="number" min="1" max="31" x-model="form.date" placeholder="1–31" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary"></div><div><label class="text-xs text-gray-500">Day</label><select x-model="form.day" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary"><option value="">Select day</option><template x-for="day in days"><option :value="day" x-text="day"></option></template></select></div><div><label class="text-xs text-gray-500">Month</label><select x-model="form.month" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary"><option value="">Select month</option><template x-for="month in months"><option :value="month" x-text="month"></option></template></select></div><div><label class="text-xs text-gray-500">Year</label><input type="number" min="2000" max="2100" x-model="form.year" placeholder="2026" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary"></div></div></div>
                            </div>
                            <div class="flex items-center justify-between border-t border-dark-700 px-6 py-5 sm:px-8"><p class="text-xs text-gray-500">Saved only in this browser session.</p><button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-hover">Save expense</button></div>
                        </form>
                    </template>
                </div>
            </div>
        </div>

        <div x-show="tagEditor.open" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 p-4" @keydown.escape.window="tagEditor.open = false"><div class="w-full max-w-sm rounded-xl border border-dark-600 bg-dark-800 p-6 shadow-2xl" @click.outside="tagEditor.open = false"><h3 class="font-mono text-lg font-semibold text-white" x-text="tagEditor.id ? 'Edit tag' : 'Add new tag'"></h3><p class="mt-1 text-sm text-gray-500" x-text="tagEditor.group === 'types' ? 'Expense type for this category.' : 'Person for this category.'"></p><input type="text" x-model="tagEditor.name" @keydown.enter.prevent="saveTag()" x-ref="tagName" placeholder="Enter a name" class="mt-5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary"><p x-show="tagEditor.error" x-text="tagEditor.error" class="mt-2 text-xs text-danger"></p><div class="mt-6 flex justify-end gap-3"><button type="button" @click="tagEditor.open = false" class="rounded-lg px-4 py-2 text-sm text-gray-400 hover:bg-dark-700 hover:text-white">Cancel</button><button type="button" @click="saveTag()" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary-hover">Save tag</button></div></div></div>
    </div>

    <script>
        window.expensesV1 = () => ({
            expenses: [], modal: { open: false, step: 'categories' }, tagEditor: { open: false, group: '', id: null, name: '', error: '' }, form: null,
            days: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'], months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            categories: [
                { id: 'lpg', name: 'LPG', icon: '♨', description: 'Filling charges and actual LPG filling cost.', types: [{id:'filling_charges',name:'Filling Charges'},{id:'filling_cost',name:'Filling Cost'}] },
                { id: 'vehicle', name: 'Vehicle', icon: '▣', description: 'Fuel, oil change, and air pressure.', types: [{id:'fuel',name:'Fuel'},{id:'oil_change',name:'Oil Change'},{id:'air_pressure',name:'Air Pressure'}] },
                { id: 'warehouse', name: 'Warehouse', icon: '⌂', description: 'Stationery, rent, cleaning, and equipment.', types: [{id:'stationery',name:'Stationery'},{id:'rent',name:'Rent'},{id:'cleaning',name:'Cleaning'},{id:'equipment',name:'Equipment'}] },
                { id: 'bike', name: 'Bike', icon: '⌁', description: 'Fuel cost based on trip distance and average.', fuel: true, people: [{id:'usman',name:'Usman'},{id:'younus',name:'Younus'},{id:'ikram',name:'Ikram'}], types: [{id:'fuel_cost',name:'Fuel Cost'}] },
                { id: 'car', name: 'Car', icon: '▱', description: 'Fuel cost based on trip distance and average.', fuel: true, types: [{id:'fuel_cost',name:'Fuel Cost'}] },
                { id: 'travel', name: 'Travel', icon: '⌁', description: 'Ring Road toll tax and society entry fee.', types: [{id:'ring_road_toll_tax',name:'Ring Road Toll Tax'},{id:'society_entry_fee',name:'Society Entry Fee'}] },
            ],
            defaultPeople() { return [{id:'usman',name:'Usman'},{id:'bilal',name:'Bilal'},{id:'younus',name:'Younus'},{id:'ikram',name:'Ikram'}]; },
            openCategories() { this.modal = { open: true, step: 'categories' }; }, closeModal() { this.modal.open = false; },
            chooseCategory(category) { this.form = { category, types: category.types.map(type => ({...type})), people: (category.people || this.defaultPeople()).map(person => ({...person})), type: category.types[0].id, payers: [], driver: '', amounts: {}, cylinders: '', distance: '', average: '', rate: '', date: '', day: '', month: '', year: '' }; this.modal.step = 'form'; },
            selectPerson(id) { if (this.form.category.fuel) { this.form.driver = id; return; } this.form.payers = this.form.payers.includes(id) ? this.form.payers.filter(item => item !== id) : [...this.form.payers, id]; }, personSelected(id) { return this.form.category.fuel ? this.form.driver === id : this.form.payers.includes(id); },
            fuelCost() { const d = Number(this.form?.distance), a = Number(this.form?.average), r = Number(this.form?.rate); return d > 0 && a > 0 && r >= 0 ? (d / a) * r : null; }, formatPkr(amount) { return `PKR ${Number(amount || 0).toLocaleString('en-PK', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`; },
            openTagEditor(group, tag = null) { this.tagEditor = { open: true, group, id: tag?.id || null, name: tag?.name || '', error: '' }; this.$nextTick(() => this.$refs.tagName?.focus()); },
            saveTag() { const name = this.tagEditor.name.trim(), list = this.form[this.tagEditor.group]; if (!name) { this.tagEditor.error = 'Please enter a name.'; return; } if (list.some(tag => tag.name.toLowerCase() === name.toLowerCase() && tag.id !== this.tagEditor.id)) { this.tagEditor.error = 'This tag already exists.'; return; } if (this.tagEditor.id) list.find(tag => tag.id === this.tagEditor.id).name = name; else list.push({id: `tag-${Date.now()}`, name}); this.tagEditor.open = false; },
            removeTag(group, id) { const item = this.form[group].find(tag => tag.id === id); if (!item || !window.confirm(`Remove “${item.name}”?`)) return; this.form[group] = this.form[group].filter(tag => tag.id !== id); if (group === 'types' && this.form.type === id) this.form.type = ''; if (group === 'people') { this.form.payers = this.form.payers.filter(payer => payer !== id); if (this.form.driver === id) this.form.driver = ''; delete this.form.amounts[id]; } },
            saveExpense() { const category = this.form.category, type = this.form.types.find(type => type.id === this.form.type)?.name || 'Not selected', people = category.fuel ? this.form.people.find(person => person.id === this.form.driver)?.name || 'Not selected' : this.form.people.filter(person => this.form.payers.includes(person.id)).map(person => person.name).join(', ') || 'Not selected', amount = category.fuel ? this.fuelCost() || 0 : Object.values(this.form.amounts).reduce((sum, value) => sum + Number(value || 0), 0), date = [this.form.date, this.form.month, this.form.year].filter(Boolean).join(' ') || 'Not entered'; this.expenses.unshift({ id: Date.now(), category: category.name, type, person: people, amount, date }); this.closeModal(); },
        });
    </script>
</x-layouts.admin>
