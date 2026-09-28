<?php

namespace App\Livewire\Admin\IndusGas\Staff;

use App\Models\IndusGas\Staff;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class StaffForm extends Component
{
    public ?Staff $staff = null;
    public string $name = '';
    public string $designation = '';
    public ?string $joining_date = null;
    public ?string $leaving_date = null;
    public ?string $salary_package = null;

    public function mount(?Staff $staff = null): void
    {
        $this->staff = $staff;
        if ($staff) {
            $this->name = $staff->name;
            $this->designation = $staff->designation;
            $this->joining_date = $staff->joining_date?->toDateString();
            $this->leaving_date = $staff->leaving_date?->toDateString();
            $this->salary_package = (string) $staff->salary_package;
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:150'],
            'designation' => ['required', 'string', 'max:150'],
            'joining_date' => ['nullable', 'date'],
            'leaving_date' => ['nullable', 'date', 'after_or_equal:joining_date'],
            'salary_package' => ['required', 'numeric', 'min:0'],
        ]);

        ($this->staff ?? new Staff)->fill($data)->save();
        $this->redirectRoute('admin.indus-gas.staff', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.staff.form');
    }
}
