<?php

namespace App\Livewire\Admin\IndusGas\Customers;

use App\Models\IndusGas\Customer;
use App\Models\IndusGas\CylinderType;
use App\Services\IndusGasCustomerService;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class CustomerForm extends Component
{
    public ?Customer $customer = null;

    public string $title = '';

    public string $location = '';

    public string $contact_person = '';

    public string $phone = '';

    public string $email = '';

    public string $city = '';

    public string $billing_address = '';

    public string $type = 'non_gst';

    public string $ntn = '';

    public string $payment_term = 'cash';

    public ?int $payment_due_days = null;

    public string $whatsapp_group_name = '';

    public string $whatsapp_group_url = '';

    public string $notes = '';

    public bool $is_active = true;

    public array $allocations = [];

    public function mount(?Customer $customer = null): void
    {
        if ($customer?->exists) {
            $this->customer = $customer;
            foreach (['title', 'location', 'contact_person', 'phone', 'email', 'city', 'billing_address', 'type', 'ntn', 'payment_term', 'whatsapp_group_name', 'whatsapp_group_url', 'notes', 'is_active'] as $field) {
                $this->{$field} = $customer->{$field} ?? '';
            }
            $this->payment_due_days = $customer->payment_due_days;

            $this->allocations = $customer->cylinderAllocations()
                ->pluck('quantity', 'cylinder_type_id')
                ->map(fn ($quantity) => (int) $quantity)
                ->all();
        }
    }

    public function save(IndusGasCustomerService $service): void
    {
        $this->phone = preg_replace('/[\s-]+/', '', $this->phone);

        $validated = $this->validate([
            'title' => ['required', 'string', 'max:150'],
            'location' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'phone' => ['required', 'string', 'regex:/^(?:\\+92|0)3\\d{9}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'billing_address' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', 'in:gst,non_gst'],
            'ntn' => ['nullable', 'string', 'max:50'],
            'payment_term' => ['required', 'in:cash,bill_to_bill,weekly,monthly,custom'],
            'payment_due_days' => [Rule::requiredIf($this->payment_term === 'custom'), 'nullable', 'integer', 'min:0', 'max:365'],
            'whatsapp_group_name' => ['nullable', 'string', 'max:150'],
            'whatsapp_group_url' => ['nullable', 'url', 'max:2048'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['boolean'],
            'allocations.*' => ['nullable', 'integer', 'min:0', 'max:999'],
        ], [
            'phone.regex' => 'Enter a valid Pakistani mobile number, e.g. 03001234567 or +923001234567.',
        ]);

        $allocations = Arr::only($this->allocations, CylinderType::query()->pluck('id')->map(fn ($id) => (string) $id)->all());
        unset($validated['allocations']);

        if ($this->customer) {
            $service->update($this->customer, $validated, $allocations);
            $message = 'Customer updated successfully.';
        } else {
            $service->create($validated, $allocations);
            $message = 'Customer created successfully.';
        }

        session()->flash('success', $message);
        $this->redirect(route('admin.indus-gas.customers'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.customers.form', [
            'cylinderTypes' => CylinderType::query()->orderByDesc('capacity_kg')->get(),
        ]);
    }
}
