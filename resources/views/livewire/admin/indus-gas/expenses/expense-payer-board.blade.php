<div>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <a href="{{ route('admin.indus-gas.expenses') }}" class="text-sm text-primary-light hover:text-white">← Back to daily expenses</a>
            <h1 class="mt-3 font-mono text-2xl font-bold text-white">PAYERS</h1>
            <p class="mt-1 text-sm text-gray-500">Expenses paid by each staff member on {{ \Carbon\Carbon::parse($date, 'Asia/Karachi')->format('d M Y') }}.</p>
        </div>
        <div class="rounded-lg border border-dark-700 bg-dark-800 px-4 py-3 text-right">
            <p class="text-xs uppercase tracking-wide text-gray-500">Business day</p>
            <p class="mt-1 font-mono text-sm text-white">{{ \Carbon\Carbon::parse($date, 'Asia/Karachi')->format('d M Y') }}</p>
        </div>
    </div>

    <div class="mt-6 flex min-h-[calc(100vh-270px)] gap-5 overflow-x-auto pb-4">
        @forelse ($payers as $payer)
            <section class="flex w-80 shrink-0 flex-col overflow-hidden rounded-xl border border-dark-700 bg-dark-800">
                <header class="border-b border-dark-700 bg-dark-900 px-4 py-4">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="truncate font-mono text-sm font-semibold uppercase tracking-wider text-white" title="{{ $payer['name'] }}">{{ $payer['name'] }}</h2>
                        <span class="shrink-0 rounded-full bg-dark-700 px-2 py-0.5 text-xs text-gray-400">{{ count($payer['expenses']) }}</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">{{ count($payer['expenses']) === 1 ? '1 expense paid' : count($payer['expenses']).' expenses paid' }}</p>
                </header>

                <div class="flex-1 space-y-3 overflow-y-auto p-3">
                    @forelse ($payer['expenses'] as $expense)
                        <article class="rounded-lg border border-dark-600 bg-dark-700 p-3">
                            <p class="text-sm font-medium text-white">{{ $expense['category'] }}</p>
                            <p class="mt-1 text-xs uppercase tracking-wide text-gray-500">{{ $expense['type'] }}</p>
                            <p class="mt-3 font-mono text-sm text-primary-light">PKR {{ number_format($expense['amount'], 2) }}</p>
                        </article>
                    @empty
                        <div class="rounded-lg border border-dashed border-dark-600 p-4 text-center text-sm text-gray-500">
                            No expenses paid on this day.
                        </div>
                    @endforelse
                </div>

                <footer class="mt-auto flex items-center justify-between border-t border-dark-700 bg-dark-900 px-4 py-4">
                    <span class="text-xs font-medium uppercase tracking-wide text-gray-500">Total paid</span>
                    <span class="font-mono text-base text-primary-light">PKR {{ number_format($payer['total'], 2) }}</span>
                </footer>
            </section>
        @empty
            <div class="w-full rounded-xl border border-dashed border-dark-600 p-10 text-center text-sm text-gray-500">
                No staff members found. Add staff members before using the Payers board.
            </div>
        @endforelse
    </div>
</div>
