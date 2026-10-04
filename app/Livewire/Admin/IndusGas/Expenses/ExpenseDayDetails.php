<?php

namespace App\Livewire\Admin\IndusGas\Expenses;

use App\Models\IndusGas\Expense;
use App\Models\IndusGas\ExpenseDayClosing;
use App\Models\IndusGas\ExpenseReimbursement;
use App\Models\IndusGas\PartnerSettlement;
use App\Models\IndusGas\Staff;
use App\Services\IndusGasDailyPayerSummaryService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ExpenseDayDetails extends Component
{
    public string $date;
    public bool $showReimbursementForm = false;
    public bool $showPartnerSettlementForm = false;
    public ?int $reimbursementRecipientId = null;
    public ?int $reimbursementPayerId = null;
    public ?int $partnerPayerId = null;
    public ?int $partnerRecipientId = null;
    public string $amount = '';
    public string $paymentMethod = 'cash';
    public string $notes = '';

    public function mount(Expense $expense): void
    {
        $this->date = $expense->expense_date->toDateString();
    }

    public function openReimbursement(int $recipientId): void
    {
        $this->resetValidation();
        $this->reset('amount', 'notes');
        $this->reimbursementRecipientId = $recipientId;
        $this->reimbursementPayerId = Staff::query()->where('role', 'owner')->value('id');
        $this->paymentMethod = 'cash';
        $this->showReimbursementForm = true;
    }

    public function saveReimbursement(IndusGasDailyPayerSummaryService $summaryService): void
    {
        $this->validate([
            'reimbursementRecipientId' => ['required', 'integer', 'exists:indus_gas_staff,id'],
            'reimbursementPayerId' => ['required', 'integer', 'exists:indus_gas_staff,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'paymentMethod' => ['required', 'in:cash,bank_transfer,online'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $recipient = Staff::query()->whereKey($this->reimbursementRecipientId)->where('role', 'employee')->first();
        $payer = Staff::query()->whereKey($this->reimbursementPayerId)->whereIn('role', ['owner', 'partner'])->first();
        if (! $recipient || ! $payer) {
            $this->addError('reimbursementRecipientId', 'Choose an employee and an owner or partner.');
            return;
        }

        $balance = collect($summaryService->closingSummary($this->date)['staff_balances'])->firstWhere('id', $recipient->id);
        if (! $balance || (float) $this->amount > (float) $balance['pending']) {
            $this->addError('amount', 'Amount cannot be more than the employee’s pending balance.');
            return;
        }

        ExpenseReimbursement::create([
            'business_date' => $this->date,
            'paid_by_staff_id' => $payer->id,
            'received_by_staff_id' => $recipient->id,
            'amount' => $this->amount,
            'payment_method' => $this->paymentMethod,
            'notes' => $this->notes ?: null,
            'paid_at' => now('Asia/Karachi'),
        ]);
        $this->showReimbursementForm = false;
        session()->flash('success', "{$recipient->name}'s reimbursement was recorded.");
    }

    public function openPartnerSettlement(IndusGasDailyPayerSummaryService $summaryService): void
    {
        $summary = $summaryService->closingSummary($this->date);
        if (! $summary['owner'] || ! $summary['partner']) {
            $this->addError('partnerSettlement', 'Add one Owner and one Partner in Staff before recording a settlement.');
            return;
        }

        $this->resetValidation();
        $this->reset('amount', 'notes');
        $this->partnerPayerId = $summary['partner_balance'] >= 0 ? $summary['partner']['id'] : $summary['owner']['id'];
        $this->partnerRecipientId = $summary['partner_balance'] >= 0 ? $summary['owner']['id'] : $summary['partner']['id'];
        $this->paymentMethod = 'bank_transfer';
        $this->showPartnerSettlementForm = true;
    }

    public function savePartnerSettlement(): void
    {
        $this->validate([
            'partnerPayerId' => ['required', 'integer', 'different:partnerRecipientId', 'exists:indus_gas_staff,id'],
            'partnerRecipientId' => ['required', 'integer', 'exists:indus_gas_staff,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'paymentMethod' => ['required', 'in:cash,bank_transfer,online'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $validPeople = Staff::query()->whereIn('id', [$this->partnerPayerId, $this->partnerRecipientId])
            ->whereIn('role', ['owner', 'partner'])->count() === 2;
        if (! $validPeople) {
            $this->addError('partnerPayerId', 'A settlement must be between the Owner and Partner.');
            return;
        }

        PartnerSettlement::create([
            'business_date' => $this->date,
            'paid_by_staff_id' => $this->partnerPayerId,
            'received_by_staff_id' => $this->partnerRecipientId,
            'amount' => $this->amount,
            'payment_method' => $this->paymentMethod,
            'notes' => $this->notes ?: null,
            'settled_at' => now('Asia/Karachi'),
        ]);
        $this->showPartnerSettlementForm = false;
        session()->flash('success', 'Partner settlement was recorded.');
    }

    public function closeDay(IndusGasDailyPayerSummaryService $summaryService): void
    {
        $summary = $summaryService->closingSummary($this->date);
        if ($summary['staff_pending_total'] > 0.009) {
            $this->addError('closing', 'Clear all employee reimbursements before closing this day.');
            return;
        }
        if (abs($summary['partner_balance']) > 0.009) {
            $this->addError('closing', 'Record the remaining Owner/Partner settlement before closing this day.');
            return;
        }

        ExpenseDayClosing::updateOrCreate(['business_date' => $this->date], [
            'closed_by_staff_id' => $summary['owner']['id'] ?? null,
            'closed_at' => now('Asia/Karachi'),
        ]);
        session()->flash('success', 'Business day closed successfully.');
    }

    public function render()
    {
        $service = app(IndusGasDailyPayerSummaryService::class);

        return view('livewire.admin.indus-gas.expenses.expense-day-details', [
            'expenses' => Expense::query()->with(['category', 'type', 'responsibleStaff'])->where('expense_date', $this->date)->latest('id')->get(),
            'staffNames' => Staff::query()->pluck('name', 'id'),
            'staffMembers' => Staff::query()->orderBy('name')->get(['id', 'name', 'role']),
            'summary' => $service->closingSummary($this->date),
            'closing' => ExpenseDayClosing::query()->where('business_date', $this->date)->first(),
        ]);
    }
}
