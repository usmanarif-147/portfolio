<?php

namespace App\Services;

use App\Models\IndusGas\Expense;
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
            ->get(['id', 'name']);

        $payers = [];
        foreach ($staff as $member) {
            $payers[$member->id] = [
                'id' => $member->id,
                'name' => $member->name,
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
}
