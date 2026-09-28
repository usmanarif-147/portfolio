<?php

namespace App\Livewire\Admin\IndusGas\Staff;

use App\Models\IndusGas\Staff;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class StaffIndex extends Component
{
    public function delete(int $id): void
    {
        Staff::query()->findOrFail($id)->delete();
        session()->flash('success', 'Staff member removed successfully.');
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.staff.index', [
            'staffMembers' => Staff::query()->latest()->get(),
        ]);
    }
}
