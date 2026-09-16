<?php

namespace App\Livewire\Admin\IndusGas\Settings;

use App\Models\IndusGas\CylinderType;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class CylinderTypeIndex extends Component
{
    public function delete(int $id): void
    {
        CylinderType::query()->findOrFail($id)->delete();
        session()->flash('success', 'Cylinder type deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.settings.cylinder-type-index', ['cylinderTypes' => CylinderType::query()->orderBy('capacity_kg', 'desc')->get()]);
    }
}
