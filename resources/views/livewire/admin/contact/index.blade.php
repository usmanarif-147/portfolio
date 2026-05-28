<div>
    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
        <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-gray-300 transition-colors">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-300">Contacts</span>
    </div>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-mono font-bold text-white uppercase tracking-wider">Contacts</h1>
            <p class="text-gray-500 mt-1">Messages submitted via the public contact form.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or email..."
                       class="w-full bg-dark-700 border border-dark-600 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-dark-700/50">
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Date</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Name</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Email</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Message</th>
                        <th class="text-left text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Status</th>
                        <th class="text-right text-xs font-mono font-medium text-gray-400 uppercase tracking-wider px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-700">
                    @forelse ($contacts as $contact)
                        <tr class="hover:bg-dark-700/30 transition-colors {{ ! $contact->is_read ? 'bg-primary/5' : '' }}">
                            <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">{{ $contact->created_at->format('M d, Y g:i A') }}</td>
                            <td class="px-6 py-4 text-sm text-white font-medium">{{ $contact->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-400">{{ $contact->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-400">{{ \Illuminate\Support\Str::limit($contact->message, 60) }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($contact->is_read)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-dark-700 text-gray-400">Read</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-primary/20 text-primary-light">New</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="viewContact({{ $contact->id }})"
                                            class="text-gray-400 hover:text-primary-light transition-colors p-1" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <x-admin.confirm-button
                                        title="Delete Contact?"
                                        text="This contact message will be permanently removed."
                                        action="$wire.delete({{ $contact->id }})"
                                        confirm-text="Yes, delete it"
                                        class="text-gray-400 hover:text-red-400 transition-colors p-1"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </x-admin.confirm-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No contact messages yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($contacts->hasPages())
            <div class="px-6 py-4 border-t border-dark-700">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if ($selectedContact)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data x-transition.opacity
             wire:key="contact-modal-{{ $selectedContact->id }}">
            <div class="fixed inset-0 bg-black/60" wire:click="closeModal"></div>

            <div class="relative bg-dark-800 border border-dark-700 rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-start justify-between px-6 py-4 border-b border-dark-700">
                    <div>
                        <h2 class="text-lg font-mono font-bold text-white uppercase tracking-wider">Contact Message</h2>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $selectedContact->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-white transition-colors p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Name</p>
                        <p class="text-sm text-white">{{ $selectedContact->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</p>
                        <p class="text-sm text-white">
                            <a href="mailto:{{ $selectedContact->email }}" class="hover:text-primary-light transition-colors">{{ $selectedContact->email }}</a>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Message</p>
                        <p class="text-sm text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $selectedContact->message }}</p>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-dark-700 flex justify-end">
                    <button type="button" wire:click="closeModal"
                            class="bg-dark-700 hover:bg-dark-600 text-gray-300 font-medium rounded-lg px-4 py-2 transition-colors text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
