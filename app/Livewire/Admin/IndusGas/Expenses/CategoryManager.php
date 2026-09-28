<?php
namespace App\Livewire\Admin\IndusGas\Expenses;
use App\Models\IndusGas\ExpenseCategory;
use App\Models\IndusGas\ExpenseType;
use App\Models\IndusGas\Staff;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
class CategoryManager extends Component {
 public bool $open=false; public ?int $categoryId=null; public string $name=''; public string $expense_type_display='tags'; public string $staff_display='tags'; public bool $is_active=true; public array $types=['']; public array $staffIds=[];
 #[On('open-expense-category-manager')] public function create(): void { $this->reset(['categoryId','name','staffIds']); $this->types=['']; $this->expense_type_display='tags';$this->staff_display='tags';$this->is_active=true;$this->open=true; }
 public function edit(int $id): void { $c=ExpenseCategory::with(['types','staff'])->findOrFail($id);$this->categoryId=$id;$this->name=$c->name;$this->types=$c->types->pluck('name')->all() ?: [''];$this->staffIds=$c->staff->pluck('id')->all();$this->expense_type_display=$c->expense_type_display;$this->staff_display=$c->staff_display;$this->is_active=$c->is_active;$this->open=true; }
 public function addType(): void {$this->types[]='';} public function removeType(int $i): void {unset($this->types[$i]);$this->types=array_values($this->types) ?: [''];}
 public function save(): void { $this->types=array_values(array_filter(array_map('trim',$this->types)));$this->validate(['name'=>'required|string|max:150','types'=>'array|min:1','types.*'=>'required|string|max:150','staffIds'=>'array','staffIds.*'=>'integer|exists:indus_gas_staff,id','expense_type_display'=>'in:tags,dropdown','staff_display'=>'in:tags,dropdown']); if(count($this->types)!==count(array_unique(array_map('strtolower',$this->types)))){$this->addError('types','Expense types must be unique.');return;} $c=$this->categoryId?ExpenseCategory::findOrFail($this->categoryId):new ExpenseCategory;$c->fill(['name'=>$this->name,'slug'=>Str::slug($this->name),'expense_type_display'=>$this->expense_type_display,'staff_display'=>$this->staff_display,'is_active'=>$this->is_active])->save();$c->staff()->sync($this->staffIds);$c->types()->delete();foreach($this->types as $type){$c->types()->create(['name'=>$type,'slug'=>Str::slug($type),'is_active'=>true]);}$this->open=false;$this->dispatch('expense-categories-updated'); }
 public function render(){return view('livewire.admin.indus-gas.expenses.category-manager',['categories'=>ExpenseCategory::with(['types','staff'])->orderBy('name')->get(),'staff'=>Staff::orderBy('name')->get()]);}
}
