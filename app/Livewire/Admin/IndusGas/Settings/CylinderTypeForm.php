<?php

namespace App\Livewire\Admin\IndusGas\Settings;

use App\Models\IndusGas\CylinderType;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class CylinderTypeForm extends Component
{
    public ?CylinderType $cylinderType = null;

    public string $name = '';

    public string $category = 'commercial';

    public string $capacity_kg = '';

    public bool $is_active = true;

    public function mount(?CylinderType $cylinderType = null): void
    {
        if ($cylinderType?->exists) {
            $this->cylinderType = $cylinderType;
            $this->name = $cylinderType->name;
            $this->category = $cylinderType->category;
            $this->capacity_kg = $cylinderType->capacity_kg;
            $this->is_active = $cylinderType->is_active;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('indus_gas_cylinder_types')->ignore($this->cylinderType)],
            'category' => ['required', Rule::in(['commercial', 'domestic'])],
            'capacity_kg' => ['required', 'numeric', 'gt:0', 'max:9999.99'],
            'is_active' => ['boolean'],
        ]);

        ($this->cylinderType ?? new CylinderType)->fill($validated)->save();
        session()->flash('success', 'Cylinder type '.($this->cylinderType ? 'updated' : 'created').' successfully.');
        $this->redirectRoute('admin.indus-gas.settings.cylinder-types', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.settings.cylinder-type-form');
    }
}
