<?php

namespace App\Services;

use App\Models\IndusGas\Expense;
use App\Models\IndusGas\ExpenseReimbursement;
use App\Models\IndusGas\PartnerSettlement;
use App\Models\IndusGas\Staff;

class IndusGasDailyPayerSummaryService
{
    /**
     * Build one business day's payer report from the payment amounts saved on
     * each expense. Amounts are accumulated in paisa to keep PKR totals exact.
     */
    public function forDate(string $date): array
    {
        $staff = Staff::query()
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        $payers = [];
        foreach ($staff as $member) {
            $payers[$member->id] = [
                'id' => $member->id,
                'name' => $member->name,
                'role' => $member->role,
                'total_paisa' => 0,
                'expenses' => [],
            ];
        }

        $expenses = Expense::query()
            ->where('expense_date', $date)
            ->with([
                'category:id,name',
                'type:id,name',
                'responsibleStaff:id,name',
            ])
            ->orderBy('id')
            ->get([
                'id',
                'expense_category_id',
                'expense_type_id',
                'responsible_staff_id',
                'amount',
                'payer_amounts',
            ]);

        foreach ($expenses as $expense) {
            if ($expense->responsible_staff_id) {
                $this->addExpense(
                    $payers,
                    $expense->responsible_staff_id,
                    $expense->responsibleStaff?->name,
                    $expense,
                    $expense->amount,
                );

                continue;
            }

            foreach ($expense->payer_amounts ?? [] as $staffId => $amount) {
                $this->addExpense($payers, (int) $staffId, null, $expense, $amount);
            }
        }

        return array_map(function (array $payer): array {
            return [
                'id' => $payer['id'],
                'name' => $payer['name'],
                'role' => $payer['role'],
                'total' => $payer['total_paisa'] / 100,
                'expenses' => $payer['expenses'],
            ];
        }, array_values($payers));
    }

    private function addExpense(array &$payers, int $staffId, ?string $staffName, Expense $expense, mixed $amount): void
    {
        if (! isset($payers[$staffId])) {
            $payers[$staffId] = [
                'id' => $staffId,
            'name' => $staffName ?: "Former staff #{$staffId}",
                'role' => 'former',
                'total_paisa' => 0,
                'expenses' => [],
            ];
        }

        $paisa = (int) round(((float) $amount) * 100);

        $payers[$staffId]['total_paisa'] += $paisa;
        $payers[$staffId]['expenses'][] = [
            'id' => $expense->id,
            'category' => $expense->category?->name ?? 'Category removed',
            'type' => $expense->type?->name ?? 'Type removed',
            'amount' => $paisa / 100,
        ];
    }

    /**
     * Returns the daily business cost, staff reimbursement balances and the
     * 50/50 Owner/Partner settlement position. Reimbursements and partner
     * transfers are money movements, never a second business expense.
     */
    public function closingSummary(string $date): array
    {
        $payers = $this->forDate($date);

        $reimbursements = ExpenseReimbursement::query()
            ->where('business_date', $date)
            ->get(['paid_by_staff_id', 'received_by_staff_id', 'amount']);
        $partnerSettlements = PartnerSettlement::query()
            ->where('business_date', $date)
            ->get(['paid_by_staff_id', 'received_by_staff_id', 'amount']);

        $reimbursedTo = $reimbursements->groupBy('received_by_staff_id')
            ->map(fn ($items) => $this->paisa($items->sum('amount')))->all();
        $reimbursedBy = $reimbursements->groupBy('paid_by_staff_id')
            ->map(fn ($items) => $this->paisa($items->sum('amount')))->all();

        $staffBalances = [];
        foreach ($payers as $payer) {
            if ($payer['role'] !== 'employee') {
                continue;
            }

            $paid = $this->paisa($payer['total']);
            $reimbursed = $reimbursedTo[$payer['id']] ?? 0;
            $staffBalances[] = [
                'id' => $payer['id'],
                'name' => $payer['name'],
                'paid' => $paid / 100,
                'reimbursed' => $reimbursed / 100,
                'pending' => max(0, $paid - $reimbursed) / 100,
            ];
        }

        $owner = collect($payers)->firstWhere('role', 'owner');
        $partner = collect($payers)->firstWhere('role', 'partner');
        $ownerFunding = $owner ? $this->paisa($owner['total']) + ($reimbursedBy[$owner['id']] ?? 0) : 0;
        $partnerFunding = $partner ? $this->paisa($partner['total']) + ($reimbursedBy[$partner['id']] ?? 0) : 0;
        $baseBalance = ($ownerFunding - $partnerFunding) / 2;

        $settlementTowardsOwner = 0;
        if ($owner && $partner) {
            foreach ($partnerSettlements as $settlement) {
                $amount = $this->paisa($settlement->amount);
                if ($settlement->paid_by_staff_id === $partner['id'] && $settlement->received_by_staff_id === $owner['id']) {
                    $settlementTowardsOwner += $amount;
                }
                if ($settlement->paid_by_staff_id === $owner['id'] && $settlement->received_by_staff_id === $partner['id']) {
                    $settlementTowardsOwner -= $amount;
                }
            }
        }

        $balance = $baseBalance - $settlementTowardsOwner;

        return [
            'business_total' => array_sum(array_column($payers, 'total')),
            'staff_balances' => $staffBalances,
            'staff_pending_total' => array_sum(array_column($staffBalances, 'pending')),
            'owner' => $owner ? ['id' => $owner['id'], 'name' => $owner['name'], 'funded' => $ownerFunding / 100] : null,
            'partner' => $partner ? ['id' => $partner['id'], 'name' => $partner['name'], 'funded' => $partnerFunding / 100] : null,
            'partner_balance' => $balance / 100,
            'partner_settlements' => $partnerSettlements->map(fn ($settlement) => [
                'paid_by_staff_id' => $settlement->paid_by_staff_id,
                'received_by_staff_id' => $settlement->received_by_staff_id,
                'amount' => (float) $settlement->amount,
            ])->all(),
        ];
    }

    private function paisa(mixed $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }
}
