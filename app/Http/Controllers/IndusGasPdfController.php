<?php

namespace App\Http\Controllers;

use App\Models\IndusGas\BusinessProfile;
use App\Models\IndusGas\Customer;
use App\Models\IndusGas\Invoice;
use App\Models\IndusGas\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class IndusGasPdfController extends Controller
{
    public function invoice(Invoice $invoice)
    {
        $invoice->load('customer', 'delivery.items.cylinderType');

        return Pdf::loadView('indus-gas.pdf.invoice', ['invoice' => $invoice, 'profile' => BusinessProfile::first()])->setPaper('a4')->download($invoice->invoice_number.'.pdf');
    }

    public function ledger(Customer $customer)
    {
        $entries = collect(Invoice::where('customer_id', $customer->id)->get()->map(fn ($i) => ['date' => $i->invoice_date, 'description' => 'Invoice '.$i->invoice_number, 'debit' => $i->total_amount, 'credit' => 0]))->merge(Payment::where('customer_id', $customer->id)->get()->map(fn ($p) => ['date' => $p->payment_date, 'description' => 'Payment '.$p->reference, 'debit' => 0, 'credit' => $p->amount]))->sortBy('date')->values();

        return Pdf::loadView('indus-gas.pdf.ledger', compact('customer', 'entries'))->setPaper('a4')->download('ledger-'.$customer->id.'.pdf');
    }
}
