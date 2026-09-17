<?php

namespace App\Livewire\Admin\IndusGas\Expenses;

use App\Models\IndusGas\CashAdvance;
use App\Models\IndusGas\Expense;
use App\Models\IndusGas\ExpenseCategory;
use App\Models\IndusGas\PartnerSettlement;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')] class ExpenseIndex extends Component
{
    public bool $showExpenseModal = false;

    public bool $showCategoryModal = false;

    public ?int $expense_category_id = null;

    public string $expense_date;

    public string $amount = '';

    public string $paid_by = 'younus';

    public string $description = '';

    public string $notes = '';

    public string $category_name = '';

    public function mount(): void
    {
        $this->expense_date = now()->toDateString();
    }

    public function saveExpense(): void
    {
        $d = $this->validate(['expense_category_id' => 'required|exists:indus_gas_expense_categories,id', 'expense_date' => 'required|date', 'amount' => 'required|numeric|gt:0', 'paid_by' => 'required|in:usman,bilal,younus', 'description' => 'nullable|string|max:150', 'notes' => 'nullable|string|max:2000']);
        $d['reimbursement_status'] = $d['paid_by'] === 'younus' ? 'pending' : 'not_required';
        Expense::create($d);
        $this->showExpenseModal = false;
        session()->flash('success', 'Expense saved.');
    }

    public function addCategory(): void
    {
        $this->validate(['category_name' => 'required|string|max:100|unique:indus_gas_expense_categories,name']);
        $c = ExpenseCategory::create(['name' => $this->category_name]);
        $this->expense_category_id = $c->id;
        $this->category_name = '';
        $this->showCategoryModal = false;
    }

    public function clear(int $id): void
    {
        Expense::findOrFail($id)->update(['reimbursement_status' => 'cleared', 'reimbursed_by' => 'usman', 'reimbursed_at' => now()]);
    }

    public function render()
    {
        $e = Expense::with('category')->latest('expense_date')->get();
        $sum = fn ($d) => $e->where('expense_date', '>=', $d)->sum('amount');
        $net = ($e->where('paid_by', 'usman')->sum('amount') - $e->where('paid_by', 'bilal')->sum('amount')) / 2 - PartnerSettlement::all()->sum(fn ($s) => $s->paid_by === 'bilal' ? $s->amount : -$s->amount);

        return view('livewire.admin.indus-gas.expenses.index', ['expenses' => $e, 'categories' => ExpenseCategory::where('is_active', true)->get(), 'today' => $sum(today()), 'yesterday' => $e->where('expense_date', today()->subDay()->toDateString())->sum('amount'), 'week' => $sum(today()->subDays(6)), 'month' => $sum(today()->subDays(29)), 'total' => $e->sum('amount'), 'pending' => $e->where('reimbursement_status', 'pending')->sum('amount') - CashAdvance::sum('amount'), 'partnerNet' => $net]);
    }
}
