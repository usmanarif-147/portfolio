<?php
namespace App\Livewire\Admin\IndusGas\Expenses;
use App\Models\IndusGas\Expense; use App\Models\IndusGas\Staff; use Carbon\Carbon; use Livewire\Attributes\Layout; use Livewire\Component;
#[Layout('components.layouts.admin')]
class ExpenseDayDetails extends Component { public string $date; public function mount(string $date):void{$this->date=Carbon::createFromFormat('Y-m-d',$date,'Asia/Karachi')->toDateString();} public function render(){return view('livewire.admin.indus-gas.expenses.expense-day-details',['expenses'=>Expense::with(['category','type','responsibleStaff'])->whereDate('expense_date',$this->date)->latest('id')->get(),'staffNames'=>Staff::pluck('name','id')]);}}
