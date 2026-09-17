<?php

namespace App\Livewire\Admin\IndusGas\Billing;

use App\Models\IndusGas\Customer;
use App\Models\IndusGas\Invoice;
use App\Services\IndusGasBillingService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')] class PaymentForm extends Component
{
    public ?int $customer_id = null;

    public string $payment_date;

    public string $amount = '';

    public string $payment_method = 'online';

    public string $reference = '';

    public string $notes = '';

    public array $allocations = [];

    public function mount(): void
    {
        $this->payment_date = now()->toDateString();
    }

    public function save(IndusGasBillingService $service): void
    {
        $data = $this->validate(['customer_id' => ['required', 'exists:indus_gas_customers,id'], 'payment_date' => ['required', 'date'], 'amount' => ['required', 'numeric', 'gt:0'], 'payment_method' => ['required', 'in:cash,online,cheque'], 'reference' => ['nullable', 'string', 'max:150'], 'notes' => ['nullable', 'string', 'max:2000'], 'allocations.*' => ['nullable', 'numeric', 'min:0']]);
        $allocated = array_sum($this->allocations);
        if ($allocated > $data['amount']) {
            $this->addError('allocations', 'Allocated amount cannot be more than payment amount.');

            return;
        }unset($data['allocations']);
        $service->createPayment($data, $this->allocations);
        session()->flash('success', 'Payment recorded successfully.');
        $this->redirectRoute('admin.indus-gas.payments-ledgers', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.billing.payment-form', ['customers' => Customer::query()->where('is_active', true)->orderBy('title')->get(), 'invoices' => $this->customer_id ? Invoice::query()->where('customer_id', $this->customer_id)->latest('invoice_date')->get() : collect()]);
    }
}
