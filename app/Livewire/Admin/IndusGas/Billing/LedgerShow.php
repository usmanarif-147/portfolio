<?php

namespace App\Livewire\Admin\IndusGas\Billing;

use App\Models\IndusGas\Customer;
use App\Models\IndusGas\Invoice;
use App\Models\IndusGas\Payment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')] class LedgerShow extends Component
{
    public Customer $customer;

    public function mount(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function render()
    {
        $entries = collect(Invoice::where('customer_id', $this->customer->id)->get()->map(fn ($i) => ['date' => $i->invoice_date, 'type' => 'Invoice', 'number' => $i->invoice_number, 'debit' => $i->total_amount, 'credit' => 0]))->merge(Payment::where('customer_id', $this->customer->id)->get()->map(fn ($p) => ['date' => $p->payment_date, 'type' => 'Payment', 'number' => $p->reference ?: '—', 'debit' => 0, 'credit' => $p->amount]))->sortBy('date')->values();

        return view('livewire.admin.indus-gas.billing.ledger-show', compact('entries'));
    }
}
