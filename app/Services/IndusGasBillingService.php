<?php

namespace App\Services;

use App\Models\IndusGas\BusinessProfile;
use App\Models\IndusGas\Delivery;
use App\Models\IndusGas\Invoice;
use App\Models\IndusGas\Payment;
use Illuminate\Support\Facades\DB;

class IndusGasBillingService
{
    public function createInvoice(Delivery $delivery): Invoice
    {
        return DB::transaction(function () use ($delivery) {
            $delivery->loadMissing('items.cylinderType');
            $profile = BusinessProfile::first();
            $next = (Invoice::lockForUpdate()->max('id') ?? 0) + 1;
            $kg = $delivery->items->sum(fn ($i) => $i->delivered_quantity * $i->cylinderType->capacity_kg);

            return Invoice::create(['delivery_id' => $delivery->id, 'customer_id' => $delivery->customer_id, 'invoice_number' => ($profile?->invoice_prefix ?? 'IG-').str_pad((string) $next, 5, '0', STR_PAD_LEFT), 'invoice_date' => $delivery->delivery_date, 'total_kg' => $kg, 'rate_per_kg' => $delivery->sale_rate_per_kg, 'total_amount' => $kg * $delivery->sale_rate_per_kg, 'notes' => $delivery->notes]);
        });
    }

    public function createPayment(array $data, array $allocations): Payment
    {
        return DB::transaction(function () use ($data, $allocations) {
            $payment = Payment::create($data);
            foreach ($allocations as $id => $amount) {
                if ($amount > 0) {
                    $payment->invoices()->attach($id, ['amount' => $amount]);
                }
            }

return $payment;
        });
    }
}
