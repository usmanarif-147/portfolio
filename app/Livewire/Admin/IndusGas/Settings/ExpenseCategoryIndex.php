<?php

namespace App\Livewire\Admin\IndusGas\Settings;

use App\Models\IndusGas\ExpenseCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ExpenseCategoryIndex extends Component
{
    public function delete(int $id): void
    {
        ExpenseCategory::query()->findOrFail($id)->delete();
        session()->flash('success', 'Expense category deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.settings.expense-category-index', ['expenseCategories' => ExpenseCategory::query()->orderBy('name')->get()]);
    }
}
