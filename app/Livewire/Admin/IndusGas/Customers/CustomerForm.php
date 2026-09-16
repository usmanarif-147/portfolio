<?php

namespace App\Livewire\Admin\IndusGas\Customers;

use App\Models\IndusGas\Customer;
use App\Services\IndusGasCustomerService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class CustomerForm extends Component
{
    public ?Customer $customer = null;

    public string $title = '';

    public string $location = '';

    public string $phone = '';

    public string $type = 'non_gst';

    public function mount(?Customer $customer = null): void
    {
        if ($customer?->exists) {
            $this->customer = $customer;
            $this->title = $customer->title;
            $this->location = $customer->location;
            $this->phone = $customer->phone;
            $this->type = $customer->type;
        }
    }

    public function save(IndusGasCustomerService $service): void
    {
        $this->phone = preg_replace('/[\s-]+/', '', $this->phone);

        $validated = $this->validate([
            'title' => ['required', 'string', 'max:150'],
            'location' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(?:\\+92|0)3\\d{9}$/'],
            'type' => ['required', 'in:gst,non_gst'],
        ], [
            'phone.regex' => 'Enter a valid Pakistani mobile number, e.g. 03001234567 or +923001234567.',
        ]);

        if ($this->customer) {
            $service->update($this->customer, $validated);
            $message = 'Customer updated successfully.';
        } else {
            $service->create($validated);
            $message = 'Customer created successfully.';
        }

        session()->flash('success', $message);
        $this->redirect(route('admin.indus-gas.customers'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.customers.form');
    }
}
