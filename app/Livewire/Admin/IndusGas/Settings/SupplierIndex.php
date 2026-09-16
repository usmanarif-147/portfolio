<?php

namespace App\Livewire\Admin\IndusGas\Settings;

use App\Models\IndusGas\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class SupplierIndex extends Component
{
    public function delete(int $id): void
    {
        Supplier::query()->findOrFail($id)->delete();
        session()->flash('success', 'Supplier deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.settings.supplier-index', ['suppliers' => Supplier::query()->orderBy('name')->get()]);
    }
}
