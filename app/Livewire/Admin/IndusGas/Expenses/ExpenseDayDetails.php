<?php
namespace App\Livewire\Admin\IndusGas\Expenses;
use App\Models\IndusGas\Expense; use App\Models\IndusGas\Staff; use Livewire\Attributes\Layout; use Livewire\Component;
#[Layout('components.layouts.admin')]
class ExpenseDayDetails extends Component { public string $date; public function mount(Expense $expense):void{$this->date=$expense->expense_date->toDateString();} public function render(){return view('livewire.admin.indus-gas.expenses.expense-day-details',['expenses'=>Expense::with(['category','type','responsibleStaff'])->whereDate('expense_date',$this->date)->latest('id')->get(),'staffNames'=>Staff::pluck('name','id')]);}}
