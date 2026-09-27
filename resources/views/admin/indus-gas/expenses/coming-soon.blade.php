<x-layouts.admin>
    <div x-data="{ activeTab: 'LPG' }">
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300">Dashboard</a>
            <span>›</span>
            <span>Indus Gas</span>
            <span>›</span>
            <span class="text-gray-300">Expenses</span>
        </div>

        <div class="mb-8">
            <h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider">Expenses</h1>
            <p class="mt-1 text-gray-500">Choose an expense area to continue.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-4">
            <aside class="rounded-xl border border-dark-700 bg-dark-800 p-3 lg:col-span-1">
                <p class="px-3 pb-3 pt-2 text-xs font-semibold uppercase tracking-wider text-gray-500">Expense areas</p>

                <div class="flex gap-2 overflow-x-auto pb-1 lg:block lg:space-y-1 lg:overflow-visible" role="tablist" aria-label="Expense areas">
                    @foreach (['LPG', 'Vehicle', 'Warehouse', 'Bike', 'Car', 'Travel', 'Bilal', 'Usman', 'Younus', 'Ikram', 'Driver'] as $tab)
                        <button type="button" role="tab" @click="activeTab = '{{ $tab }}'"
                            :aria-selected="activeTab === '{{ $tab }}'"
                            :class="activeTab === '{{ $tab }}' ? 'bg-primary/15 text-primary-light ring-1 ring-inset ring-primary/30' : 'text-gray-400 hover:bg-dark-700 hover:text-white'"
                            class="group flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm font-medium transition-colors lg:w-full">
                            <span :class="activeTab === '{{ $tab }}' ? 'bg-primary text-white' : 'bg-dark-700 text-gray-400 group-hover:text-gray-200'"
                                class="flex h-8 w-8 items-center justify-center rounded-full transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            {{ $tab }}
                        </button>
                    @endforeach
                </div>
            </aside>

            <div class="lg:col-span-3">
                @foreach (['LPG', 'Vehicle', 'Warehouse', 'Bike', 'Car', 'Travel', 'Bilal', 'Usman', 'Younus', 'Ikram', 'Driver'] as $tab)
                    <section x-show="activeTab === '{{ $tab }}'" x-cloak x-transition.opacity.duration.150ms
                        class="min-h-80 rounded-xl border border-dark-700 bg-dark-800 p-6 sm:p-8" role="tabpanel"
                        aria-label="{{ $tab }} expenses" @if (in_array($tab, ['LPG', 'Vehicle', 'Warehouse', 'Travel'])) x-data="{ expenseType: '{{ $tab === 'LPG' ? 'filling_charges' : ($tab === 'Vehicle' ? 'fuel' : ($tab === 'Warehouse' ? 'stationery' : 'ring_road_toll_tax')) }}', payers: [], amounts: {} }" @endif>
                        <div class="border-b border-dark-700 pb-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-primary-light">Expense area</p>
                            <h2 class="mt-2 text-xl font-mono font-semibold text-white">{{ $tab }} Expenses</h2>
                        </div>

                        @if ($tab === 'LPG')
                            <form class="mt-6 space-y-7" @submit.prevent>
                                <div>
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">LPG expense type</label>
                                        <span class="text-xs text-gray-500">Choose one</span>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-3">
                                        <button type="button" @click="expenseType = 'filling_charges'"
                                            :class="expenseType === 'filling_charges' ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'"
                                            class="rounded-lg border px-4 py-3 text-left text-sm font-medium transition-colors">
                                            <span class="block">Filling Charges</span>
                                            <span class="mt-0.5 block text-xs font-normal opacity-75">Service charges</span>
                                        </button>
                                        <button type="button" @click="expenseType = 'filling_cost'"
                                            :class="expenseType === 'filling_cost' ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'"
                                            class="rounded-lg border px-4 py-3 text-left text-sm font-medium transition-colors">
                                            <span class="block">Filling Cost</span>
                                            <span class="mt-0.5 block text-xs font-normal opacity-75">Actual LPG cost</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="max-w-sm">
                                    <label for="lpg-cylinder-count" class="text-sm font-medium text-gray-200">Total number of cylinders</label>
                                    <input id="lpg-cylinder-count" type="number" min="1" inputmode="numeric" placeholder="For example: 50"
                                        class="mt-2 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                </div>

                                <div class="border-t border-dark-700 pt-6">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Who paid?</label>
                                        <span class="text-xs text-gray-500">Select one or more people</span>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach (['Usman', 'Bilal', 'Younus', 'Ikram'] as $person)
                                            <button type="button"
                                                @click="payers.includes('{{ $person }}') ? payers = payers.filter(person => person !== '{{ $person }}') : payers.push('{{ $person }}')"
                                                :class="payers.includes('{{ $person }}') ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'"
                                                class="rounded-full border px-4 py-2 text-sm font-medium transition-colors">
                                                {{ $person }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <div x-show="payers.length === 0" class="mt-3 text-sm text-gray-500">Select a person to enter the amount they paid.</div>

                                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        @foreach (['Usman', 'Bilal', 'Younus', 'Ikram'] as $person)
                                            <div x-show="payers.includes('{{ $person }}')" x-cloak x-transition.opacity.duration.150ms>
                                                <label for="lpg-paid-{{ strtolower($person) }}" class="text-sm font-medium text-gray-200">{{ $person }} paid</label>
                                                <div class="relative mt-2">
                                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-500">PKR</span>
                                                    <input id="lpg-paid-{{ strtolower($person) }}" type="number" min="0" inputmode="decimal" x-model="amounts['{{ $person }}']" placeholder="0"
                                                        class="block w-full rounded-lg border border-dark-600 bg-dark-900 py-2.5 pl-12 pr-3 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="border-t border-dark-700 pt-6">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Expense date</label>
                                        <span class="text-xs text-gray-500">Enter each part separately</span>
                                    </div>
                                    <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                                        <div>
                                            <label for="lpg-date" class="text-xs text-gray-500">Date</label>
                                            <input id="lpg-date" type="number" min="1" max="31" inputmode="numeric" placeholder="1–31"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                        <div>
                                            <label for="lpg-day" class="text-xs text-gray-500">Day</label>
                                            <select id="lpg-day" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="">Select day</option>
                                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <option value="{{ strtolower($day) }}">{{ $day }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="lpg-month" class="text-xs text-gray-500">Month</label>
                                            <select id="lpg-month" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="">Select month</option>
                                                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                                    <option value="{{ strtolower($month) }}">{{ $month }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="lpg-year" class="text-xs text-gray-500">Year</label>
                                            <input id="lpg-year" type="number" min="2000" max="2100" inputmode="numeric" placeholder="2026"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end border-t border-dark-700 pt-6">
                                    <button type="button" disabled class="cursor-not-allowed rounded-lg bg-dark-700 px-5 py-2.5 text-sm font-medium text-gray-500">Save LPG expense (coming soon)</button>
                                </div>
                            </form>
                        @elseif ($tab === 'Vehicle')
                            <form class="mt-6 space-y-7" @submit.prevent>
                                <div>
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Vehicle expense type</label>
                                        <span class="text-xs text-gray-500">Choose one</span>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-3">
                                        @foreach ([['fuel', 'Fuel'], ['oil_change', 'Oil Change'], ['air_pressure', 'Air Pressure']] as [$value, $label])
                                            <button type="button" @click="expenseType = '{{ $value }}'"
                                                :class="expenseType === '{{ $value }}' ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'"
                                                class="rounded-lg border px-4 py-3 text-left text-sm font-medium transition-colors">
                                                {{ $label }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="border-t border-dark-700 pt-6">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Who paid?</label>
                                        <span class="text-xs text-gray-500">Select one or more people</span>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach (['Usman', 'Bilal', 'Younus', 'Ikram'] as $person)
                                            <button type="button"
                                                @click="payers.includes('{{ $person }}') ? payers = payers.filter(person => person !== '{{ $person }}') : payers.push('{{ $person }}')"
                                                :class="payers.includes('{{ $person }}') ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'"
                                                class="rounded-full border px-4 py-2 text-sm font-medium transition-colors">
                                                {{ $person }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <div x-show="payers.length === 0" class="mt-3 text-sm text-gray-500">Select a person to enter the amount they paid.</div>

                                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        @foreach (['Usman', 'Bilal', 'Younus', 'Ikram'] as $person)
                                            <div x-show="payers.includes('{{ $person }}')" x-cloak x-transition.opacity.duration.150ms>
                                                <label for="vehicle-paid-{{ strtolower($person) }}" class="text-sm font-medium text-gray-200">{{ $person }} paid</label>
                                                <div class="relative mt-2">
                                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-500">PKR</span>
                                                    <input id="vehicle-paid-{{ strtolower($person) }}" type="number" min="0" inputmode="decimal" x-model="amounts['{{ $person }}']" placeholder="0"
                                                        class="block w-full rounded-lg border border-dark-600 bg-dark-900 py-2.5 pl-12 pr-3 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="border-t border-dark-700 pt-6">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Expense date</label>
                                        <span class="text-xs text-gray-500">Enter each part separately</span>
                                    </div>
                                    <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                                        <div>
                                            <label for="vehicle-date" class="text-xs text-gray-500">Date</label>
                                            <input id="vehicle-date" type="number" min="1" max="31" inputmode="numeric" placeholder="1–31"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                        <div>
                                            <label for="vehicle-day" class="text-xs text-gray-500">Day</label>
                                            <select id="vehicle-day" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="">Select day</option>
                                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <option value="{{ strtolower($day) }}">{{ $day }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="vehicle-month" class="text-xs text-gray-500">Month</label>
                                            <select id="vehicle-month" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="">Select month</option>
                                                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                                    <option value="{{ strtolower($month) }}">{{ $month }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="vehicle-year" class="text-xs text-gray-500">Year</label>
                                            <input id="vehicle-year" type="number" min="2000" max="2100" inputmode="numeric" placeholder="2026"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end border-t border-dark-700 pt-6">
                                    <button type="button" disabled class="cursor-not-allowed rounded-lg bg-dark-700 px-5 py-2.5 text-sm font-medium text-gray-500">Save vehicle expense (coming soon)</button>
                                </div>
                            </form>
                        @elseif ($tab === 'Warehouse')
                            <form class="mt-6 space-y-7" @submit.prevent>
                                <div>
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Warehouse expense type</label>
                                        <span class="text-xs text-gray-500">Choose one</span>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-3">
                                        @foreach ([['stationery', 'Stationery'], ['rent', 'Rent'], ['cleaning', 'Cleaning'], ['equipment', 'Equipment']] as [$value, $label])
                                            <button type="button" @click="expenseType = '{{ $value }}'"
                                                :class="expenseType === '{{ $value }}' ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'"
                                                class="rounded-lg border px-4 py-3 text-left text-sm font-medium transition-colors">
                                                {{ $label }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="border-t border-dark-700 pt-6">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Who paid?</label>
                                        <span class="text-xs text-gray-500">Select one or more people</span>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach (['Usman', 'Bilal', 'Younus', 'Ikram'] as $person)
                                            <button type="button"
                                                @click="payers.includes('{{ $person }}') ? payers = payers.filter(person => person !== '{{ $person }}') : payers.push('{{ $person }}')"
                                                :class="payers.includes('{{ $person }}') ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'"
                                                class="rounded-full border px-4 py-2 text-sm font-medium transition-colors">
                                                {{ $person }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <div x-show="payers.length === 0" class="mt-3 text-sm text-gray-500">Select a person to enter the amount they paid.</div>

                                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        @foreach (['Usman', 'Bilal', 'Younus', 'Ikram'] as $person)
                                            <div x-show="payers.includes('{{ $person }}')" x-cloak x-transition.opacity.duration.150ms>
                                                <label for="warehouse-paid-{{ strtolower($person) }}" class="text-sm font-medium text-gray-200">{{ $person }} paid</label>
                                                <div class="relative mt-2">
                                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-500">PKR</span>
                                                    <input id="warehouse-paid-{{ strtolower($person) }}" type="number" min="0" inputmode="decimal" x-model="amounts['{{ $person }}']" placeholder="0"
                                                        class="block w-full rounded-lg border border-dark-600 bg-dark-900 py-2.5 pl-12 pr-3 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="border-t border-dark-700 pt-6">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Expense date</label>
                                        <span class="text-xs text-gray-500">Enter each part separately</span>
                                    </div>
                                    <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                                        <div>
                                            <label for="warehouse-date" class="text-xs text-gray-500">Date</label>
                                            <input id="warehouse-date" type="number" min="1" max="31" inputmode="numeric" placeholder="1–31"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                        <div>
                                            <label for="warehouse-day" class="text-xs text-gray-500">Day</label>
                                            <select id="warehouse-day" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="">Select day</option>
                                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <option value="{{ strtolower($day) }}">{{ $day }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="warehouse-month" class="text-xs text-gray-500">Month</label>
                                            <select id="warehouse-month" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="">Select month</option>
                                                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                                    <option value="{{ strtolower($month) }}">{{ $month }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="warehouse-year" class="text-xs text-gray-500">Year</label>
                                            <input id="warehouse-year" type="number" min="2000" max="2100" inputmode="numeric" placeholder="2026"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end border-t border-dark-700 pt-6">
                                    <button type="button" disabled class="cursor-not-allowed rounded-lg bg-dark-700 px-5 py-2.5 text-sm font-medium text-gray-500">Save warehouse expense (coming soon)</button>
                                </div>
                            </form>
                        @elseif ($tab === 'Travel')
                            <form class="mt-6 space-y-7" @submit.prevent>
                                <div>
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Travel expense type</label>
                                        <span class="text-xs text-gray-500">Choose one</span>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-3">
                                        @foreach ([['ring_road_toll_tax', 'Ring Road Toll Tax'], ['society_entry_fee', 'Society Entry Fee']] as [$value, $label])
                                            <button type="button" @click="expenseType = '{{ $value }}'"
                                                :class="expenseType === '{{ $value }}' ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'"
                                                class="rounded-lg border px-4 py-3 text-left text-sm font-medium transition-colors">
                                                {{ $label }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="border-t border-dark-700 pt-6">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Who paid?</label>
                                        <span class="text-xs text-gray-500">Select one or more people</span>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach (['Usman', 'Bilal', 'Younus', 'Ikram'] as $person)
                                            <button type="button"
                                                @click="payers.includes('{{ $person }}') ? payers = payers.filter(person => person !== '{{ $person }}') : payers.push('{{ $person }}')"
                                                :class="payers.includes('{{ $person }}') ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'"
                                                class="rounded-full border px-4 py-2 text-sm font-medium transition-colors">
                                                {{ $person }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <div x-show="payers.length === 0" class="mt-3 text-sm text-gray-500">Select a person to enter the amount they paid.</div>

                                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        @foreach (['Usman', 'Bilal', 'Younus', 'Ikram'] as $person)
                                            <div x-show="payers.includes('{{ $person }}')" x-cloak x-transition.opacity.duration.150ms>
                                                <label for="travel-paid-{{ strtolower($person) }}" class="text-sm font-medium text-gray-200">{{ $person }} paid</label>
                                                <div class="relative mt-2">
                                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-500">PKR</span>
                                                    <input id="travel-paid-{{ strtolower($person) }}" type="number" min="0" inputmode="decimal" x-model="amounts['{{ $person }}']" placeholder="0"
                                                        class="block w-full rounded-lg border border-dark-600 bg-dark-900 py-2.5 pl-12 pr-3 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="border-t border-dark-700 pt-6">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Expense date</label>
                                        <span class="text-xs text-gray-500">Enter each part separately</span>
                                    </div>
                                    <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                                        <div>
                                            <label for="travel-date" class="text-xs text-gray-500">Date</label>
                                            <input id="travel-date" type="number" min="1" max="31" inputmode="numeric" placeholder="1–31"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                        <div>
                                            <label for="travel-day" class="text-xs text-gray-500">Day</label>
                                            <select id="travel-day" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="">Select day</option>
                                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <option value="{{ strtolower($day) }}">{{ $day }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="travel-month" class="text-xs text-gray-500">Month</label>
                                            <select id="travel-month" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="">Select month</option>
                                                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                                    <option value="{{ strtolower($month) }}">{{ $month }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="travel-year" class="text-xs text-gray-500">Year</label>
                                            <input id="travel-year" type="number" min="2000" max="2100" inputmode="numeric" placeholder="2026"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end border-t border-dark-700 pt-6">
                                    <button type="button" disabled class="cursor-not-allowed rounded-lg bg-dark-700 px-5 py-2.5 text-sm font-medium text-gray-500">Save travel expense (coming soon)</button>
                                </div>
                            </form>
                        @elseif (in_array($tab, ['Bike', 'Car']))
                            @php($drivers = $tab === 'Bike' ? ['Usman', 'Younus', 'Ikram'] : ['Usman', 'Bilal', 'Younus', 'Ikram'])
                            <form class="mt-6 space-y-7" @submit.prevent
                                x-data="{
                                    distance: '',
                                    average: '',
                                    rate: '',
                                    driver: '',
                                    get fuelCost() {
                                        const distance = Number(this.distance);
                                        const average = Number(this.average);
                                        const rate = Number(this.rate);
                                        return distance > 0 && average > 0 && rate >= 0 ? (distance / average) * rate : null;
                                    }
                                }">
                                <div>
                                    <p class="text-sm font-medium text-gray-200">Fuel calculation</p>
                                    <p class="mt-1 text-xs text-gray-500">Enter the trip details to calculate the fuel cost automatically.</p>
                                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                                        <div>
                                            <label for="{{ strtolower($tab) }}-distance" class="text-xs text-gray-500">Distance travelled (km)</label>
                                            <input id="{{ strtolower($tab) }}-distance" type="number" min="0" inputmode="decimal" x-model="distance" placeholder="For example: 10"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                        <div>
                                            <label for="{{ strtolower($tab) }}-average" class="text-xs text-gray-500">Fuel average (km per litre)</label>
                                            <input id="{{ strtolower($tab) }}-average" type="number" min="0" step="0.01" inputmode="decimal" x-model="average" placeholder="For example: 8"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                        <div>
                                            <label for="{{ strtolower($tab) }}-rate" class="text-xs text-gray-500">Fuel price per litre (PKR)</label>
                                            <input id="{{ strtolower($tab) }}-rate" type="number" min="0" step="0.01" inputmode="decimal" x-model="rate" placeholder="For example: 395.56"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-primary/25 bg-primary/10 px-5 py-4">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="text-sm font-medium text-primary-light">Fuel cost</span>
                                        <span class="text-xl font-mono font-semibold text-white" x-text="fuelCost === null ? 'Enter trip details' : 'PKR ' + fuelCost.toLocaleString('en-PK', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-400">(Distance travelled ÷ fuel average) × fuel price per litre</p>
                                </div>

                                <div class="border-t border-dark-700 pt-6">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Who was the driver?</label>
                                        <span class="text-xs text-gray-500">Choose one</span>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach ($drivers as $driver)
                                            <button type="button" @click="driver = '{{ $driver }}'"
                                                :class="driver === '{{ $driver }}' ? 'border-primary bg-primary/15 text-primary-light' : 'border-dark-600 bg-dark-900 text-gray-400 hover:border-primary/50 hover:text-gray-200'"
                                                class="rounded-full border px-4 py-2 text-sm font-medium transition-colors">
                                                {{ $driver }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="border-t border-dark-700 pt-6">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-200">Expense date</label>
                                        <span class="text-xs text-gray-500">Enter each part separately</span>
                                    </div>
                                    <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                                        <div>
                                            <label for="{{ strtolower($tab) }}-date" class="text-xs text-gray-500">Date</label>
                                            <input id="{{ strtolower($tab) }}-date" type="number" min="1" max="31" inputmode="numeric" placeholder="1–31"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                        <div>
                                            <label for="{{ strtolower($tab) }}-day" class="text-xs text-gray-500">Day</label>
                                            <select id="{{ strtolower($tab) }}-day" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="">Select day</option>
                                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <option value="{{ strtolower($day) }}">{{ $day }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="{{ strtolower($tab) }}-month" class="text-xs text-gray-500">Month</label>
                                            <select id="{{ strtolower($tab) }}-month" class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="">Select month</option>
                                                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                                    <option value="{{ strtolower($month) }}">{{ $month }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="{{ strtolower($tab) }}-year" class="text-xs text-gray-500">Year</label>
                                            <input id="{{ strtolower($tab) }}-year" type="number" min="2000" max="2100" inputmode="numeric" placeholder="2026"
                                                class="mt-1.5 block w-full rounded-lg border border-dark-600 bg-dark-900 px-3 py-2.5 text-sm text-white outline-none placeholder:text-gray-600 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end border-t border-dark-700 pt-6">
                                    <button type="button" disabled class="cursor-not-allowed rounded-lg bg-dark-700 px-5 py-2.5 text-sm font-medium text-gray-500">Save {{ strtolower($tab) }} fuel expense (coming soon)</button>
                                </div>
                            </form>
                        @else
                            <div class="flex min-h-52 flex-col items-center justify-center text-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary-light">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-2.21 0-4 1.12-4 2.5S9.79 13 12 13s4 1.12 4 2.5S14.21 18 12 18m0-10V6m0 12v-2m-7-4h14" />
                                    </svg>
                                </div>
                                <p class="mt-4 text-lg font-medium text-white">{{ $tab }} expenses</p>
                                <p class="mt-1 text-sm text-gray-500">Coming soon.</p>
                            </div>
                        @endif
                    </section>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.admin>
