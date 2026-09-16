<div>
    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
        <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-gray-300 transition-colors">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <a href="{{ route('admin.indus-gas.customers') }}" wire:navigate class="hover:text-gray-300 transition-colors">Customers</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-gray-300">{{ $customer ? 'Edit' : 'Add' }}</span>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider">{{ $customer ? 'Edit Customer' : 'Add Customer' }}</h1>
        <p class="text-gray-500 mt-1">{{ $customer ? 'Update customer details.' : 'Add a new Indus Gas customer.' }}</p>
    </div>

    <form wire:submit="save" class="max-w-2xl">
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-5">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-1.5">Title <span class="text-red-400">*</span></label>
                <input id="title" type="text" wire:model="title" maxlength="150" placeholder="e.g. ABC Restaurant" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
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
            <div>
                <label for="type" class="block text-sm font-medium text-gray-300 mb-1.5">Type <span class="text-red-400">*</span></label>
                <select id="type" wire:model="type" class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="non_gst">Non-GST</option>
                    <option value="gst">GST</option>
                </select>
                @error('type') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="bg-primary hover:bg-primary-hover text-white font-medium rounded-lg px-6 py-2.5 transition-colors">
                <span wire:loading.remove wire:target="save">{{ $customer ? 'Update Customer' : 'Save Customer' }}</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
            <a href="{{ route('admin.indus-gas.customers') }}" wire:navigate class="text-gray-400 hover:text-white font-medium rounded-lg px-6 py-2.5 transition-colors">Cancel</a>
        </div>
    </form>
</div>
