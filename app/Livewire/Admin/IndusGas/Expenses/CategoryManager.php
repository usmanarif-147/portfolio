<?php
namespace App\Livewire\Admin\IndusGas\Expenses;
use App\Models\IndusGas\ExpenseCategory;
use App\Models\IndusGas\ExpenseType;
use App\Models\IndusGas\Staff;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
class CategoryManager extends Component {
 public bool $open=false; public ?int $categoryId=null; public string $name=''; public string $expense_type_display='tags'; public string $staff_display='tags'; public bool $is_active=true; public array $expenseTypes=['']; public array $staffIds=[];
 #[On('open-expense-category-manager')] public function create(): void { $this->reset(['categoryId','name','staffIds']); $this->expenseTypes=['']; $this->expense_type_display='tags';$this->staff_display='tags';$this->is_active=true;$this->open=true; }
 public function edit(int $id): void { $c=ExpenseCategory::with(['types','staff'])->findOrFail($id);$this->categoryId=$id;$this->name=$c->name;$this->expenseTypes=$c->types->pluck('name')->all() ?: [''];$this->staffIds=$c->staff->pluck('id')->all();$this->expense_type_display=$c->expense_type_display;$this->staff_display=$c->staff_display;$this->is_active=$c->is_active;$this->open=true; }
 public function addType(): void {$this->expenseTypes[]='';}
 public function removeType($index = null): void
 {
     if ($index === null || ! array_key_exists((int) $index, $this->expenseTypes)) {
         return;
     }

     unset($this->expenseTypes[(int) $index]);
     $this->expenseTypes = array_values($this->expenseTypes);

     if ($this->expenseTypes === []) {
         $this->expenseTypes = [''];
     }
 }
 public function save(): void
 {
     $this->resetErrorBag();
     $this->name = trim($this->name);
     $this->expenseTypes = array_values(array_filter(array_map(fn ($type) => trim((string) $type), $this->expenseTypes)));

     if ($this->expenseTypes === []) {
         $this->addError('expenseTypes.0', 'Please enter at least one expense type.');
         return;
     }

     $this->validate([
         'name' => ['required', 'string', 'max:150', Rule::unique('indus_gas_expense_categories', 'name')->ignore($this->categoryId)],
         'expenseTypes' => 'array',
         'expenseTypes.*' => 'required|string|max:150',
         'staffIds' => 'array',
         'staffIds.*' => 'integer|exists:indus_gas_staff,id',
         'expense_type_display' => 'in:tags,dropdown',
         'staff_display' => 'in:tags,dropdown',
     ], [
         'name.unique' => 'This category title already exists. Please use a different title.',
     ]);

     $slug = Str::slug($this->name);
     $slugAlreadyUsed = ExpenseCategory::query()
         ->where('slug', $slug)
         ->when($this->categoryId, fn ($query) => $query->whereKeyNot($this->categoryId))
         ->exists();

     if ($slugAlreadyUsed) {
         $this->addError('name', 'This category title is too similar to an existing category. Please use a different title.');
         return;
     }

     if (count($this->expenseTypes) !== count(array_unique(array_map('strtolower', $this->expenseTypes)))) {
         $this->addError('expenseTypes.0', 'Expense types must be unique.');
         return;
     }

     $c = $this->categoryId ? ExpenseCategory::findOrFail($this->categoryId) : new ExpenseCategory;
     $c->fill(['name' => $this->name, 'slug' => $slug, 'expense_type_display' => $this->expense_type_display, 'staff_display' => $this->staff_display, 'is_active' => $this->is_active])->save();
     $c->staff()->sync($this->staffIds);
     $c->types()->delete();

     foreach ($this->expenseTypes as $type) {
         $c->types()->create(['name' => $type, 'slug' => Str::slug($type), 'is_active' => true]);
     }

     $this->open = false;
     $this->dispatch('expense-categories-updated');
 }
 public function render(){return view('livewire.admin.indus-gas.expenses.category-manager',['categories'=>ExpenseCategory::with(['types','staff'])->orderBy('name')->get(),'staff'=>Staff::orderBy('name')->get()]);}
}
