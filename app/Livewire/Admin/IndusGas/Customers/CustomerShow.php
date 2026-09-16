<?php

namespace App\Livewire\Admin\IndusGas\Customers;

use App\Models\IndusGas\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class CustomerShow extends Component
{
    public Customer $customer;

    public function mount(Customer $customer): void
    {
        $this->customer = $customer->load('cylinderAllocations.cylinderType');
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.customers.show');
    }
}
