<?php

namespace App\Livewire\Admin\IndusGas\Expenses;

use App\Models\IndusGas\Expense;
use App\Services\IndusGasDailyPayerSummaryService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ExpensePayerBoard extends Component
{
    public string $date = '';

    public array $payers = [];

    public function mount(Expense $expense): void
    {
        $this->date = $expense->expense_date->toDateString();
        $this->payers = app(IndusGasDailyPayerSummaryService::class)->forDate($this->date);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.expenses.expense-payer-board');
    }
}
