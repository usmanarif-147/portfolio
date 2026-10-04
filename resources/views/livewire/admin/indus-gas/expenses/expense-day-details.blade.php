<div>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <a href="{{ route('admin.indus-gas.expenses') }}" class="text-sm text-primary-light hover:text-white">← Back to daily expenses</a>
            <h1 class="mt-3 font-mono text-2xl font-bold text-white">{{ \Carbon\Carbon::parse($date, 'Asia/Karachi')->format('d M Y') }} EXPENSES</h1>
            <p class="mt-1 text-sm text-gray-500">Pakistan business day: 12:00 AM to 11:59 PM.</p>
        </div>
        <div class="rounded-lg border px-4 py-3 {{ $closing ? 'border-emerald-500/30 bg-emerald-500/10' : 'border-amber-500/30 bg-amber-500/10' }}">
            <p class="text-xs uppercase tracking-wide {{ $closing ? 'text-emerald-300' : 'text-amber-300' }}">{{ $closing ? 'Day closed' : 'Day open' }}</p>
            <p class="mt-1 text-sm text-white">{{ $closing ? 'All payments settled' : 'Review payments before closing' }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-5 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('success') }}</div>
    @endif
    @error('closing')<p class="mt-4 rounded-lg border border-danger/30 bg-danger/10 px-4 py-3 text-sm text-danger">{{ $message }}</p>@enderror
    @error('partnerSettlement')<p class="mt-4 rounded-lg border border-danger/30 bg-danger/10 px-4 py-3 text-sm text-danger">{{ $message }}</p>@enderror

    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-dark-700 bg-dark-800 p-4"><p class="text-xs uppercase tracking-wide text-gray-500">Business expense</p><p class="mt-2 font-mono text-xl text-white">PKR {{ number_format($summary['business_total'], 2) }}</p></div>
        <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-4"><p class="text-xs uppercase tracking-wide text-amber-300">Staff reimbursement pending</p><p class="mt-2 font-mono text-xl text-white">PKR {{ number_format($summary['staff_pending_total'], 2) }}</p></div>
        <div class="rounded-xl border border-dark-700 bg-dark-800 p-4"><p class="text-xs uppercase tracking-wide text-gray-500">{{ $summary['owner']['name'] ?? 'Owner' }} funded</p><p class="mt-2 font-mono text-xl text-white">PKR {{ number_format($summary['owner']['funded'] ?? 0, 2) }}</p></div>
        <div class="rounded-xl border border-dark-700 bg-dark-800 p-4"><p class="text-xs uppercase tracking-wide text-gray-500">{{ $summary['partner']['name'] ?? 'Partner' }} funded</p><p class="mt-2 font-mono text-xl text-white">PKR {{ number_format($summary['partner']['funded'] ?? 0, 2) }}</p></div>
    </div>

    <div class="mt-5 rounded-xl border p-5 {{ $summary['partner_balance'] > 0 ? 'border-primary/30 bg-primary/5' : ($summary['partner_balance'] < 0 ? 'border-amber-500/30 bg-amber-500/5' : 'border-emerald-500/30 bg-emerald-500/5') }}">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">Owner / Partner settlement</p>
                @if ($summary['owner'] && $summary['partner'])
                    <p class="mt-1 text-sm text-gray-300">
                        @if ($summary['partner_balance'] > 0)
                            {{ $summary['partner']['name'] }} owes {{ $summary['owner']['name'] }}
                        @elseif ($summary['partner_balance'] < 0)
                            {{ $summary['owner']['name'] }} owes {{ $summary['partner']['name'] }}
                        @else
                            Owner and Partner are settled for this day.
                        @endif
                    </p>
                @else
                    <p class="mt-1 text-sm text-gray-300">Create one Owner and one Partner in Staff to calculate this balance.</p>
                @endif
                <p class="mt-2 font-mono text-2xl text-white">PKR {{ number_format(abs($summary['partner_balance']), 2) }}</p>
            </div>
            @if (! $closing)
                <button type="button" wire:click="openPartnerSettlement" class="rounded-lg border border-primary px-4 py-2.5 text-sm text-primary-light hover:bg-primary/10">Record partner payment</button>
            @endif
        </div>
    </div>

    <div class="mt-6 rounded-xl border border-dark-700 bg-dark-800">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-dark-700 px-5 py-4"><div><h2 class="font-mono text-base text-white">STAFF REIMBURSEMENTS</h2><p class="mt-1 text-sm text-gray-500">Pay employees back for out-of-pocket business expenses.</p></div></div>
        <div class="grid divide-y divide-dark-700 md:grid-cols-2 md:divide-x md:divide-y-0">
            @forelse ($summary['staff_balances'] as $staff)
                <div class="flex items-center justify-between gap-4 p-4"><div><p class="font-medium text-white">{{ $staff['name'] }}</p><p class="mt-1 text-xs text-gray-500">Paid PKR {{ number_format($staff['paid'], 2) }} · Reimbursed PKR {{ number_format($staff['reimbursed'], 2) }}</p></div><div class="text-right"><p class="font-mono text-sm {{ $staff['pending'] > 0 ? 'text-amber-300' : 'text-emerald-300' }}">PKR {{ number_format($staff['pending'], 2) }}</p><p class="mt-1 text-xs text-gray-500">{{ $staff['pending'] > 0 ? 'Pending' : 'Cleared' }}</p>@if ($staff['pending'] > 0 && ! $closing)<button type="button" wire:click="openReimbursement({{ $staff['id'] }})" class="mt-2 text-xs text-primary-light hover:text-white">Record payment</button>@endif</div></div>
            @empty
                <p class="p-6 text-sm text-gray-500">No employee expense payments were recorded for this day.</p>
            @endforelse
        </div>
    </div>

    @if (! $closing)
        <div class="mt-5 flex justify-end"><button type="button" wire:click="closeDay" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-hover">Close business day</button></div>
    @endif

    <div class="mt-6 overflow-hidden rounded-xl border border-dark-700 bg-dark-800"><table class="min-w-full text-left text-sm"><thead class="bg-dark-900 text-gray-500"><tr><th class="p-4">Category</th><th>Expense type</th><th>Who paid</th><th>Details</th><th class="p-4 text-right">Amount</th></tr></thead><tbody>@forelse($expenses as $expense)<tr class="border-t border-dark-700 text-gray-300"><td class="p-4">{{ $expense->category->name }}</td><td>{{ $expense->type?->name }}</td><td>@if($expense->responsibleStaff){{ $expense->responsibleStaff->name }}@elseif($expense->payer_amounts)@foreach($expense->payer_amounts as $staffId=>$paidAmount)<div>{{ $staffNames[$staffId] ?? 'Staff' }}: PKR {{ number_format($paidAmount,2) }}</div>@endforeach@else—@endif</td><td class="text-xs text-gray-400">@forelse($expense->form_data ?? [] as $key=>$value)<div>{{ str_replace('_',' ',ucfirst($key)) }}: {{ $value }}</div>@empty—@endforelse</td><td class="p-4 text-right font-mono text-white">PKR {{ number_format($expense->amount,2) }}</td></tr>@empty<tr><td colspan="5" class="p-10 text-center text-gray-500">No expenses were recorded for this day.</td></tr>@endforelse</tbody></table></div>

    @if ($showReimbursementForm || $showPartnerSettlementForm)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-black/75 p-4"><div class="flex min-h-full items-center justify-center"><form wire:submit="{{ $showReimbursementForm ? 'saveReimbursement' : 'savePartnerSettlement' }}" class="w-full max-w-lg rounded-xl border border-dark-600 bg-dark-800 p-6 shadow-2xl"><div class="flex items-start justify-between"><div><p class="text-xs uppercase text-primary-light">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p><h2 class="mt-1 font-mono text-xl text-white">{{ $showReimbursementForm ? 'Record staff payment' : 'Record partner payment' }}</h2></div><button type="button" wire:click="$set('{{ $showReimbursementForm ? 'showReimbursementForm' : 'showPartnerSettlementForm' }}', false)" class="text-xl text-gray-400">×</button></div><div class="mt-6 space-y-4">@if($showReimbursementForm)<div><label class="text-sm text-gray-300">Paid by</label><select wire:model="reimbursementPayerId" class="mt-1 w-full rounded-lg border border-dark-600 bg-dark-900 p-2.5 text-white">@foreach($staffMembers->whereIn('role',['owner','partner']) as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach</select></div><div><label class="text-sm text-gray-300">Paying to</label><select wire:model="reimbursementRecipientId" class="mt-1 w-full rounded-lg border border-dark-600 bg-dark-900 p-2.5 text-white">@foreach($staffMembers->where('role','employee') as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach</select></div>@else<div><label class="text-sm text-gray-300">Paid by</label><select wire:model="partnerPayerId" class="mt-1 w-full rounded-lg border border-dark-600 bg-dark-900 p-2.5 text-white">@foreach($staffMembers->whereIn('role',['owner','partner']) as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach</select></div><div><label class="text-sm text-gray-300">Received by</label><select wire:model="partnerRecipientId" class="mt-1 w-full rounded-lg border border-dark-600 bg-dark-900 p-2.5 text-white">@foreach($staffMembers->whereIn('role',['owner','partner']) as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach</select></div>@endif<div><label class="text-sm text-gray-300">Amount (PKR)</label><input type="number" min="0.01" step="0.01" wire:model="amount" class="mt-1 w-full rounded-lg border border-dark-600 bg-dark-900 p-2.5 text-white">@error('amount')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror</div><div><label class="text-sm text-gray-300">Payment method</label><select wire:model="paymentMethod" class="mt-1 w-full rounded-lg border border-dark-600 bg-dark-900 p-2.5 text-white"><option value="cash">Cash</option><option value="bank_transfer">Bank transfer</option><option value="online">Online</option></select></div><div><label class="text-sm text-gray-300">Notes <span class="text-gray-500">(optional)</span></label><input wire:model="notes" class="mt-1 w-full rounded-lg border border-dark-600 bg-dark-900 p-2.5 text-white"></div></div><div class="mt-6 flex justify-end gap-3"><button type="button" wire:click="$set('{{ $showReimbursementForm ? 'showReimbursementForm' : 'showPartnerSettlementForm' }}', false)" class="text-sm text-gray-400">Cancel</button><button class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white">Save payment</button></div></form></div></div>
    @endif
</div>
