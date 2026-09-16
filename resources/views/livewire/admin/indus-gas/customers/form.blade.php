<div>
    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
        <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-gray-300 transition-colors">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <a href="{{ route('admin.indus-gas.customers') }}" wire:navigate class="hover:text-gray-300 transition-colors">Customers</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-gray-300">{{ $customer ? 'Edit' : 'Add' }}</span>
    </div>

    <div class="mb-8"><h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider">{{ $customer ? 'Edit Customer' : 'Add Customer' }}</h1><p class="text-gray-500 mt-1">{{ $customer ? 'Update customer details and cylinder allocation.' : 'Add a customer before recording their deliveries and payments.' }}</p></div>

    <form wire:submit="save" class="max-w-5xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-5">
            <h2 class="text-lg font-mono font-semibold text-white">Customer Details</h2>
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-1.5">Customer / Business Name <span class="text-red-400">*</span></label>
                <input id="title" type="text" wire:model="title" maxlength="150" placeholder="e.g. ABC Restaurant" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            <div><label for="contact_person" class="block text-sm font-medium text-gray-300 mb-1.5">Contact Person</label><input id="contact_person" type="text" wire:model="contact_person" maxlength="150" placeholder="e.g. Mr. Ahmad" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent">@error('contact_person') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror</div>
            <div>
                <label for="location" class="block text-sm font-medium text-gray-300 mb-1.5">Location <span class="text-red-400">*</span></label>
                <input id="location" type="text" wire:model="location" maxlength="255" placeholder="e.g. Gulberg, Lahore" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent">
                @error('location') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-300 mb-1.5">Phone Number <span class="text-red-400">*</span></label>
                <input id="phone" type="tel" wire:model="phone" inputmode="tel" placeholder="03001234567" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent">
                <p class="mt-1 text-xs text-gray-500">Use a Pakistani mobile number: 03001234567 or +923001234567.</p>
                @error('phone') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            <div class="grid sm:grid-cols-2 gap-5"><div><label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email</label><input id="email" type="email" wire:model="email" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white">@error('email') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror</div><div><label for="city" class="block text-sm font-medium text-gray-300 mb-1.5">City</label><input id="city" type="text" wire:model="city" placeholder="e.g. Lahore" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white">@error('city') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror</div></div>
            <div><label for="billing_address" class="block text-sm font-medium text-gray-300 mb-1.5">Billing Address</label><textarea id="billing_address" wire:model="billing_address" rows="3" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white"></textarea>@error('billing_address') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror</div>
        </div>
        <div class="space-y-6">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-5">
            <h2 class="text-lg font-mono font-semibold text-white">Billing & Payment</h2>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-300 mb-1.5">Invoice Type <span class="text-red-400">*</span></label>
                <select id="type" wire:model="type" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="non_gst">Non-GST</option>
                    <option value="gst">GST</option>
                </select>
                @error('type') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            <div><label for="ntn" class="block text-sm font-medium text-gray-300 mb-1.5">Customer NTN</label><input id="ntn" type="text" wire:model="ntn" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white">@error('ntn') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror</div>
            <div><label for="payment_term" class="block text-sm font-medium text-gray-300 mb-1.5">Payment Terms <span class="text-red-400">*</span></label><select id="payment_term" wire:model.live="payment_term" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white"><option value="cash">Cash / on delivery</option><option value="bill_to_bill">Bill to bill</option><option value="weekly">Weekly</option><option value="monthly">Monthly</option><option value="custom">Custom days</option></select></div>
            @if($payment_term === 'custom')<div><label for="payment_due_days" class="block text-sm font-medium text-gray-300 mb-1.5">Payment Due Within Days <span class="text-red-400">*</span></label><input id="payment_due_days" type="number" min="0" max="365" wire:model="payment_due_days" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white">@error('payment_due_days') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror</div>@endif
            <label class="flex items-center gap-3 text-sm text-gray-300"><input type="checkbox" wire:model="is_active" class="rounded border-dark-600 bg-dark-700 text-primary"> Active customer</label>
        </div>
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-5"><h2 class="text-lg font-mono font-semibold text-white">WhatsApp & Notes</h2><div><label class="block text-sm font-medium text-gray-300 mb-1.5">WhatsApp Group Name</label><input wire:model="whatsapp_group_name" placeholder="e.g. Indus Gas × Khabi Sajji" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white">@error('whatsapp_group_name') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror</div><div><label class="block text-sm font-medium text-gray-300 mb-1.5">WhatsApp Group Link</label><input wire:model="whatsapp_group_url" type="url" placeholder="https://chat.whatsapp.com/..." class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white">@error('whatsapp_group_url') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror</div><div><label class="block text-sm font-medium text-gray-300 mb-1.5">Internal Notes</label><textarea wire:model="notes" rows="3" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white"></textarea>@error('notes') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror</div></div>
        </div></div>
        <div class="mt-6 bg-dark-800 border border-dark-700 rounded-xl p-6"><h2 class="text-lg font-mono font-semibold text-white">Cylinder Allocation</h2><p class="text-sm text-gray-500 mt-1">Enter how many cylinders are reserved for this customer. This is the planned allocation, not a delivery record.</p>@if($cylinderTypes->isEmpty())<p class="mt-4 text-amber-300 text-sm">Add cylinder types in Settings before assigning cylinders.</p>@else<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-5">@foreach($cylinderTypes as $cylinderType)<div class="bg-dark-700 rounded-lg p-4"><label for="allocation-{{ $cylinderType->id }}" class="block text-sm font-medium text-white">{{ $cylinderType->name }} @if(! $cylinderType->is_active)<span class="text-xs text-amber-300">(inactive)</span>@endif</label><p class="text-xs text-gray-500 mt-1">{{ ucfirst($cylinderType->category) }} · {{ rtrim(rtrim($cylinderType->capacity_kg, '0'), '.') }} kg</p><input id="allocation-{{ $cylinderType->id }}" type="number" min="0" max="999" wire:model="allocations.{{ $cylinderType->id }}" class="mt-3 w-full bg-dark-800 border border-dark-600 rounded-lg px-4 py-2.5 text-white"><p class="mt-1 text-xs text-gray-500">Allocated cylinders</p></div>@endforeach</div>@error('allocations.*') <p class="mt-3 text-sm text-red-400">{{ $message }}</p> @enderror@endif</div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="bg-primary hover:bg-primary-hover text-white font-medium rounded-lg px-6 py-2.5 transition-colors">
                <span wire:loading.remove wire:target="save">{{ $customer ? 'Update Customer' : 'Save Customer' }}</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
            <a href="{{ route('admin.indus-gas.customers') }}" wire:navigate class="text-gray-400 hover:text-white font-medium rounded-lg px-6 py-2.5 transition-colors">Cancel</a>
        </div>
    </form>
</div>
