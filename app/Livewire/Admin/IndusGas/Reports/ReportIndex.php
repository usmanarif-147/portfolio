<?php

namespace App\Livewire\Admin\IndusGas\Reports;

use App\Models\IndusGas\Customer;
use App\Models\IndusGas\Expense;
use App\Models\IndusGas\Invoice;
use App\Models\IndusGas\Payment;
use App\Models\IndusGas\Refill;
use App\Services\IndusGasStockService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ReportIndex extends Component
{
    public string $from;

    public string $to;

    public function mount(): void
    {
        $this->from = now()->startOfMonth()->toDateString();
        $this->to = now()->endOfMonth()->toDateString();
    }

    public function apply(): void {}

    public function render(IndusGasStockService $stockService)
    {
        $invoices = Invoice::query()->with('customer')->whereBetween('invoice_date', [$this->from, $this->to])->get();
        $payments = Payment::query()->whereBetween('payment_date', [$this->from, $this->to])->get();
        $expenses = Expense::query()->with('category')->whereBetween('expense_date', [$this->from, $this->to])->get();
        $refills = Refill::query()->with(['supplier', 'items'])->whereBetween('refill_date', [$this->from, $this->to])->get();

        $refillCost = $refills->sum(fn (Refill $refill) => ($refill->filled_kg * $refill->lpg_rate_per_kg) + ($refill->items->sum('cylinder_quantity') * $refill->filling_charge_per_cylinder));
        $sales = $invoices->sum('total_amount');
        $expenseTotal = $expenses->sum('amount');
        $allInvoices = Invoice::query()->get();
        $allPayments = Payment::query()->get();
        $outstanding = $allInvoices->sum('total_amount') - $allPayments->sum('amount');

        $customers = Customer::query()->where('is_active', true)->get()->map(function (Customer $customer) use ($invoices, $payments) {
            $customerSales = $invoices->where('customer_id', $customer->id)->sum('total_amount');
            $customerPayments = $payments->where('customer_id', $customer->id)->sum('amount');

            return ['customer' => $customer, 'sales' => $customerSales, 'payments' => $customerPayments, 'period_balance' => $customerSales - $customerPayments, 'allocated' => $customer->cylinderAllocations()->sum('quantity')];
        })->sortByDesc('sales')->values();

        return view('livewire.admin.indus-gas.reports.index', compact('invoices', 'payments', 'expenses', 'refills', 'sales', 'expenseTotal', 'refillCost', 'outstanding', 'customers'))
            ->with('stock', $stockService->warehouseStock());
    }
}
