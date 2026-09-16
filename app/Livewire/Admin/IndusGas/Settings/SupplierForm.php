<?php

namespace App\Livewire\Admin\IndusGas\Settings;

use App\Models\IndusGas\Supplier;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class SupplierForm extends Component
{
    public ?Supplier $supplier = null;

    public string $name = '';

    public string $supply_type = 'lpg';

    public string $contact_person = '';

    public string $phone = '';

    public string $address = '';

    public bool $is_active = true;

    public function mount(?Supplier $supplier = null): void
    {
        if ($supplier?->exists) {
            $this->supplier = $supplier;
            foreach (['name', 'supply_type', 'contact_person', 'phone', 'address', 'is_active'] as $field) {
                $this->{$field} = $supplier->{$field} ?? '';
            }
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('indus_gas_suppliers')->ignore($this->supplier)],
            'supply_type' => ['required', Rule::in(['lpg', 'cylinders', 'both'])],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        ($this->supplier ?? new Supplier)->fill($validated)->save();
        session()->flash('success', 'Supplier '.($this->supplier ? 'updated' : 'created').' successfully.');
        $this->redirectRoute('admin.indus-gas.settings.suppliers', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.settings.supplier-form');
    }
}
