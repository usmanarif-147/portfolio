<?php

namespace App\Livewire\Admin\IndusGas\Settings;

use App\Models\IndusGas\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class VehicleIndex extends Component
{
    public function delete(int $id): void
    {
        Vehicle::query()->findOrFail($id)->delete();
        session()->flash('success', 'Vehicle deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.settings.vehicle-index', ['vehicles' => Vehicle::query()->orderBy('name')->get()]);
    }
}
