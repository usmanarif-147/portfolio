<?php

namespace App\Livewire\Admin\IndusGas\Expenses;

use App\Models\IndusGas\ExpenseCategory;
use App\Models\IndusGas\Staff;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoryManager extends Component
{
    public bool $open = false;
    public bool $is_active = true;
    public ?int $categoryId = null;
    public string $name = '';
    public array $expenseTypes = [''];
    public array $staffIds = [];
    public array $formFields = [];

    #[On('open-expense-category-manager')]
    public function openManager(?int $categoryId = null): void
    {
        $categoryId ? $this->edit($categoryId) : $this->create();
    }

    public function create(): void
    {
        $this->reset(['categoryId', 'name', 'staffIds']);
        $this->expenseTypes = [''];
        $this->formFields = [];
        $this->is_active = true;
        $this->open = true;
    }

    public function edit(int $id): void
    {
        $category = ExpenseCategory::with(['types', 'staff'])->findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->expenseTypes = $category->types->pluck('name')->all() ?: [''];
        $this->staffIds = $category->staff->pluck('id')->all();
        $this->formFields = $category->form_fields ?? [];
        $this->is_active = $category->is_active;
        $this->open = true;
    }

    public function addType(): void { $this->expenseTypes[] = ''; }
    public function removeType($index = null): void
    {
        if ($index !== null && array_key_exists((int) $index, $this->expenseTypes)) {
            unset($this->expenseTypes[(int) $index]);
            $this->expenseTypes = array_values($this->expenseTypes) ?: [''];
        }
    }
    public function addField(): void { $this->formFields[] = ['type' => 'text', 'label' => '', 'placeholder' => '', 'required' => false]; }
    public function removeField($index = null): void
    {
        if ($index !== null && array_key_exists((int) $index, $this->formFields)) {
            unset($this->formFields[(int) $index]);
            $this->formFields = array_values($this->formFields);
        }
    }

    public function save(): void
    {
        $this->resetErrorBag();
        $this->name = $this->sentenceCase($this->name);
        $this->expenseTypes = array_values(array_filter(array_map(fn ($type) => Str::upper(trim((string) $type)), $this->expenseTypes)));

        if ($this->expenseTypes === []) {
            $this->addError('expenseTypes.0', 'Add at least one expense type.');
            return;
        }

        $this->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('indus_gas_expense_categories', 'name')->ignore($this->categoryId)],
            'expenseTypes.*' => 'required|string|max:150',
            'staffIds' => 'array|min:1',
            'staffIds.*' => 'integer|exists:indus_gas_staff,id',
            'formFields' => 'array',
            'formFields.*.type' => 'required|in:text,number,decimal',
            'formFields.*.label' => 'required|string|max:100',
            'formFields.*.placeholder' => 'nullable|string|max:150',
            'formFields.*.required' => 'boolean',
        ], ['staffIds.min' => 'Select at least one staff member.', 'name.unique' => 'This category title already exists.']);

        $slug = Str::slug($this->name);
        if (ExpenseCategory::where('slug', $slug)->when($this->categoryId, fn ($query) => $query->whereKeyNot($this->categoryId))->exists()) {
            $this->addError('name', 'This category title is too similar to an existing category.');
            return;
        }
        if (count($this->expenseTypes) !== count(array_unique(array_map('strtolower', $this->expenseTypes)))) {
            $this->addError('expenseTypes.0', 'Expense types must be unique.');
            return;
        }

        $fields = [];
        foreach ($this->formFields as $field) {
            $label = $this->sentenceCase($field['label']);
            $placeholder = $this->sentenceCase($field['placeholder'] ?? '');
            $key = Str::slug($label, '_');
            if (! $key || isset($fields[$key])) {
                $this->addError('formFields', 'Each input field needs a unique label.');
                return;
            }
            $fields[$key] = ['key' => $key, 'type' => $field['type'], 'label' => $label, 'placeholder' => $placeholder, 'required' => (bool) $field['required']];
        }

        $category = $this->categoryId ? ExpenseCategory::findOrFail($this->categoryId) : new ExpenseCategory;
        $category->fill(['name' => $this->name, 'slug' => $slug, 'form_fields' => array_values($fields), 'is_active' => $this->is_active])->save();
        $category->staff()->sync($this->staffIds);
        $category->types()->delete();
        foreach ($this->expenseTypes as $type) {
            $category->types()->create(['name' => $type, 'slug' => Str::slug($type), 'is_active' => true]);
        }

        $this->open = false;
        session()->flash('success', 'Category saved successfully.');
        $this->dispatch('expense-categories-updated');
    }

    private function sentenceCase(string $value): string
    {
        $value = trim($value);
        return $value === '' ? '' : Str::ucfirst(Str::lower($value));
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.expenses.category-manager', ['categories' => ExpenseCategory::with('types')->orderBy('name')->get(), 'staff' => Staff::orderBy('name')->get()]);
    }
}
