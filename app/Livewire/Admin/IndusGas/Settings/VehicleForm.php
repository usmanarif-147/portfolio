<?php

namespace App\Livewire\Admin\IndusGas\Settings;

use App\Models\IndusGas\Vehicle;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class VehicleForm extends Component
{
    public ?Vehicle $vehicle = null;

    public string $name = '';

    public string $registration_number = '';

    public string $vehicle_type = 'Pickup';

    public string $notes = '';

    public bool $is_active = true;

    public function mount(?Vehicle $vehicle = null): void
    {
        if ($vehicle?->exists) {
            $this->vehicle = $vehicle;
            foreach (['name', 'registration_number', 'vehicle_type', 'notes', 'is_active'] as $field) {
                $this->{$field} = $vehicle->{$field} ?? '';
            }
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'registration_number' => ['required', 'string', 'max:30', Rule::unique('indus_gas_vehicles')->ignore($this->vehicle)],
            'vehicle_type' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        $validated['registration_number'] = strtoupper(trim($validated['registration_number']));
        ($this->vehicle ?? new Vehicle)->fill($validated)->save();
        session()->flash('success', 'Vehicle '.($this->vehicle ? 'updated' : 'created').' successfully.');
        $this->redirectRoute('admin.indus-gas.settings.vehicles', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.settings.vehicle-form');
    }
}
