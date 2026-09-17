<?php

namespace App\Livewire\Admin\IndusGas\Billing;

use App\Models\IndusGas\Delivery;
use App\Models\IndusGas\Invoice;
use App\Models\IndusGas\Payment;
use App\Services\IndusGasBillingService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')] class BillingIndex extends Component
{
    public function generateInvoice(IndusGasBillingService $service, int $deliveryId): void
    {
        $delivery = Delivery::findOrFail($deliveryId);
        if ($delivery->invoice) {
            $this->addError('invoice', 'Invoice already exists.');

            return;
        } $service->createInvoice($delivery);
        session()->flash('success', 'Invoice generated successfully.');
    }

    public function render()
    {
        $invoices = Invoice::query()->with('customer')->latest('invoice_date')->get();
        $payments = Payment::query()->with('customer')->latest('payment_date')->get();
        $balances = $invoices->groupBy('customer_id')->map(fn ($items, $id) => $items->sum('total_amount') - $payments->where('customer_id', $id)->sum('amount'));

        return view('livewire.admin.indus-gas.billing.index', ['invoices' => $invoices, 'pendingDeliveries' => Delivery::query()->doesntHave('invoice')->with('customer')->latest('delivery_date')->get(), 'balances' => $balances]);
    }
}
