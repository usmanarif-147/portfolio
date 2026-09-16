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

    #[Url]
    public string $status = 'active';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
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
            ->withSum('cylinderAllocations', 'quantity')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where('title', 'like', '%'.$this->search.'%')
                        ->orWhere('location', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%')
                        ->orWhere('contact_person', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->status !== 'all', fn ($query) => $query->where('is_active', $this->status === 'active'))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.indus-gas.customers.index', compact('customers'));
    }
}
