<?php

namespace App\Livewire\Admin\IndusGas\Expenses;

use App\Models\IndusGas\Expense;
use App\Models\IndusGas\ExpenseCategory;
use Livewire\Attributes\On;
use Livewire\Component;

class ExpenseIndex extends Component
{
    public bool $showForm = false;
    public ?string $defaultVehicleForm = null;
    public ?int $expenseCategoryId = null;
    public ?int $expenseTypeId = null;
    public string $expenseDate = '';
    public ?int $cylinderQuantity = null;
    public array $selectedStaffIds = [];
    public array $payerAmounts = [];
    public ?int $responsibleStaffId = null;
    public ?string $amount = null;

    public function mount(): void
    {
        $this->expenseDate = now()->toDateString();
    }

    public function openForm(): void
    {
        $this->resetExpenseForm();
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetValidation();
    }

    public function selectCategory(int $id): void
    {
        $category = ExpenseCategory::query()->with(['types', 'staff'])->where('is_active', true)->findOrFail($id);

        $this->expenseCategoryId = $category->id;
        $this->defaultVehicleForm = null;
        $this->expenseTypeId = $category->types->where('is_active', true)->first()?->id;
        $this->cylinderQuantity = null;
        $this->selectedStaffIds = [];
        $this->payerAmounts = [];
        $this->responsibleStaffId = null;
        $this->amount = null;
        $this->resetValidation();
    }

    public function selectDefaultVehicle(string $vehicle): void
    {
        if (! in_array($vehicle, ['bike', 'car'], true)) {
            return;
        }

        $this->reset(['expenseCategoryId', 'expenseTypeId', 'cylinderQuantity', 'selectedStaffIds', 'payerAmounts', 'responsibleStaffId', 'amount']);
        $this->defaultVehicleForm = $vehicle;
        $this->resetValidation();
    }

    public function togglePayer(int $staffId): void
    {
        if (in_array($staffId, $this->selectedStaffIds, true)) {
            $this->selectedStaffIds = array_values(array_filter($this->selectedStaffIds, fn ($id) => $id !== $staffId));
            unset($this->payerAmounts[$staffId]);
            return;
        }

        $this->selectedStaffIds[] = $staffId;
        $this->payerAmounts[$staffId] ??= '';
    }

    public function openCategoryManager(): void
    {
        $this->dispatch('open-expense-category-manager');
    }

    #[On('expense-categories-updated')]
    public function refreshCategories(): void
    {
        // The next render reloads categories from the database.
    }

    public function save(): void
    {
        $category = ExpenseCategory::query()->with(['types', 'staff'])->where('is_active', true)->find($this->expenseCategoryId);
        if (! $category) {
            $this->addError('expenseCategoryId', 'Please select an active expense category.');
            return;
        }

        $this->validate([
            'expenseDate' => ['required', 'date'],
            'expenseTypeId' => ['required', 'integer'],
            'cylinderQuantity' => [$category->requires_cylinder_quantity ? 'required' : 'nullable', 'nullable', 'integer', 'min:1'],
        ]);

        if (! $category->types->where('id', $this->expenseTypeId)->where('is_active', true)->isNotEmpty()) {
            $this->addError('expenseTypeId', 'Please select a valid expense type.');
            return;
        }

        $allowedStaffIds = $category->staff->pluck('id')->map(fn ($id) => (int) $id)->all();
        $payerAmounts = null;
        $responsibleStaffId = null;

        if ($category->staff_selection_mode === 'multiple_payers') {
            if ($this->selectedStaffIds === []) {
                $this->addError('selectedStaffIds', 'Select at least one person who paid.');
                return;
            }

            foreach ($this->selectedStaffIds as $staffId) {
                if (! in_array((int) $staffId, $allowedStaffIds, true)) {
                    $this->addError('selectedStaffIds', 'Only staff linked to this category can be selected.');
                    return;
                }

                if (! isset($this->payerAmounts[$staffId]) || ! is_numeric($this->payerAmounts[$staffId]) || (float) $this->payerAmounts[$staffId] < 0) {
                    $this->addError("payerAmounts.$staffId", 'Enter a valid paid amount.');
                    return;
                }
            }

            $payerAmounts = collect($this->selectedStaffIds)->mapWithKeys(fn ($staffId) => [(string) $staffId => (float) $this->payerAmounts[$staffId]])->all();
            $totalAmount = array_sum($payerAmounts);
        } else {
            if (! $this->responsibleStaffId || ! in_array($this->responsibleStaffId, $allowedStaffIds, true)) {
                $this->addError('responsibleStaffId', 'Select a staff member responsible for this expense.');
                return;
            }

            if (! is_numeric($this->amount) || (float) $this->amount < 0) {
                $this->addError('amount', 'Enter a valid expense amount.');
                return;
            }

            $responsibleStaffId = $this->responsibleStaffId;
            $totalAmount = (float) $this->amount;
        }

        Expense::create([
            'expense_category_id' => $category->id,
            'expense_type_id' => $this->expenseTypeId,
            'responsible_staff_id' => $responsibleStaffId,
            'expense_date' => $this->expenseDate,
            'amount' => $totalAmount,
            'cylinder_quantity' => $this->cylinderQuantity,
            'payer_amounts' => $payerAmounts,
        ]);

        $this->showForm = false;
        $this->resetExpenseForm();
        session()->flash('success', 'Expense saved successfully.');
    }

    private function resetExpenseForm(): void
    {
        $this->reset(['defaultVehicleForm', 'expenseCategoryId', 'expenseTypeId', 'cylinderQuantity', 'selectedStaffIds', 'payerAmounts', 'responsibleStaffId', 'amount']);
        $this->expenseDate = now()->toDateString();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.expenses.expense-index', [
            'categories' => ExpenseCategory::query()->with(['types' => fn ($query) => $query->where('is_active', true), 'staff'])->where('is_active', true)->orderBy('name')->get(),
            'selectedCategory' => $this->expenseCategoryId ? ExpenseCategory::query()->with(['types' => fn ($query) => $query->where('is_active', true), 'staff'])->find($this->expenseCategoryId) : null,
            'expenses' => Expense::query()->with(['category', 'type', 'responsibleStaff'])->latest('expense_date')->latest('id')->get(),
        ]);
    }
}
