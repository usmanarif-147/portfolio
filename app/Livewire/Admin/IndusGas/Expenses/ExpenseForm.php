<?php

namespace App\Livewire\Admin\IndusGas\Expenses;

use App\Models\IndusGas\Expense;
use App\Models\IndusGas\ExpenseCategory;
use App\Models\IndusGas\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class ExpenseForm extends Component
{
    use WithFileUploads;

    public ?int $expense_category_id = null;

    public ?int $vehicle_id = null;

    public string $expense_date;

    public string $amount = '';

    public string $payment_method = 'cash';

    public string $payee = '';

    public string $paid_by = '';

    public string $notes = '';

    public $receipt = null;

    public function mount(): void
    {
        $this->expense_date = now()->toDateString();
    }

    public function save(): void
    {
        $data = $this->validate(['expense_category_id' => ['required', 'exists:indus_gas_expense_categories,id'], 'vehicle_id' => ['nullable', 'exists:indus_gas_vehicles,id'], 'expense_date' => ['required', 'date'], 'amount' => ['required', 'numeric', 'gt:0'], 'payment_method' => ['required', 'in:cash,online,cheque'], 'payee' => ['nullable', 'string', 'max:150'], 'paid_by' => ['nullable', 'string', 'max:150'], 'notes' => ['nullable', 'string', 'max:2000'], 'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120']]);
        if ($this->receipt) {
            $data['receipt_path'] = $this->receipt->store('indus-gas/expenses', 'public');
        }
        unset($data['receipt']);
        Expense::create($data);
        session()->flash('success', 'Expense recorded successfully.');
        $this->redirectRoute('admin.indus-gas.expenses', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.expenses.form', ['categories' => ExpenseCategory::query()->where('is_active', true)->orderBy('name')->get(), 'vehicles' => Vehicle::query()->where('is_active', true)->orderBy('name')->get()]);
    }
}
