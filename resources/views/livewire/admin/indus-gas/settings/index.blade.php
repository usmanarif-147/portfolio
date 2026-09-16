<div>
    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
        <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-gray-300">Dashboard</a><span>›</span><span>Indus Gas</span><span>›</span><span class="text-gray-300">Settings</span>
    </div>
    <div class="mb-8"><h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider">Indus Gas Settings</h1><p class="text-gray-500 mt-1">Set up the business information used in daily operations.</p></div>
    @if (session('success'))<div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>@endif
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach ([
            ['route' => 'admin.indus-gas.settings.business-profile', 'title' => 'Business Profile', 'description' => 'Company details and invoice preferences.', 'status' => $isBusinessProfileComplete ? 'Configured' : 'Needs attention'],
            ['route' => 'admin.indus-gas.settings.suppliers', 'title' => 'Suppliers', 'description' => 'BBN Plant, FAS Tube, and future suppliers.', 'status' => $supplierCount.' added'],
            ['route' => 'admin.indus-gas.settings.cylinder-types', 'title' => 'Cylinder Types', 'description' => 'Commercial and domestic cylinder capacities.', 'status' => $cylinderTypeCount.' added'],
            ['route' => 'admin.indus-gas.settings.vehicles', 'title' => 'Vehicles', 'description' => 'Pickup and future delivery vehicles.', 'status' => $vehicleCount.' added'],
            ['route' => 'admin.indus-gas.settings.expense-categories', 'title' => 'Expense Categories', 'description' => 'Categories used when recording expenses.', 'status' => $expenseCategoryCount.' added'],
        ] as $item)
            <a href="{{ route($item['route']) }}" wire:navigate class="block bg-dark-800 border border-dark-700 hover:border-primary/60 rounded-xl p-6 transition-colors">
                <div class="flex items-start justify-between gap-4"><h2 class="text-lg font-mono font-semibold text-white">{{ $item['title'] }}</h2><span class="text-xs rounded-full bg-primary/10 text-primary-light px-2.5 py-1 whitespace-nowrap">{{ $item['status'] }}</span></div>
                <p class="mt-3 text-sm text-gray-500">{{ $item['description'] }}</p><p class="mt-5 text-sm font-medium text-primary-light">Manage →</p>
            </a>
        @endforeach
    </div>
</div>
