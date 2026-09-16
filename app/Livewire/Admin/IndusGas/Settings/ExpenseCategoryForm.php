<?php

namespace App\Livewire\Admin\IndusGas\Settings;

use App\Models\IndusGas\ExpenseCategory;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ExpenseCategoryForm extends Component
{
    public ?ExpenseCategory $expenseCategory = null;

    public string $name = '';

    public string $description = '';

    public bool $is_active = true;

    public function mount(?ExpenseCategory $expenseCategory = null): void
    {
        if ($expenseCategory?->exists) {
            $this->expenseCategory = $expenseCategory;
            $this->name = $expenseCategory->name;
            $this->description = $expenseCategory->description ?? '';
            $this->is_active = $expenseCategory->is_active;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('indus_gas_expense_categories')->ignore($this->expenseCategory)],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        ($this->expenseCategory ?? new ExpenseCategory)->fill($validated)->save();
        session()->flash('success', 'Expense category '.($this->expenseCategory ? 'updated' : 'created').' successfully.');
        $this->redirectRoute('admin.indus-gas.settings.expense-categories', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.settings.expense-category-form');
    }
}
