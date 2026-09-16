<?php

namespace App\Livewire\Admin\IndusGas\Customers;

use App\Models\IndusGas\Customer;
use App\Services\IndusGasCustomerService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class CustomerIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(IndusGasCustomerService $service, int $id): void
    {
        $service->delete(Customer::findOrFail($id));

        session()->flash('success', 'Customer deleted successfully.');
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where('title', 'like', '%'.$this->search.'%')
                        ->orWhere('location', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.indus-gas.customers.index', compact('customers'));
    }
}
