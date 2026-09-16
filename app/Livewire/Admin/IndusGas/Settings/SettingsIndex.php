<?php

namespace App\Livewire\Admin\IndusGas\Settings;

use App\Models\IndusGas\BusinessProfile;
use App\Models\IndusGas\CylinderType;
use App\Models\IndusGas\ExpenseCategory;
use App\Models\IndusGas\Supplier;
use App\Models\IndusGas\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class SettingsIndex extends Component
{
    public function render()
    {
        return view('livewire.admin.indus-gas.settings.index', [
            'isBusinessProfileComplete' => BusinessProfile::query()->whereNotNull('phone')->exists(),
            'supplierCount' => Supplier::query()->count(),
            'cylinderTypeCount' => CylinderType::query()->count(),
            'vehicleCount' => Vehicle::query()->count(),
            'expenseCategoryCount' => ExpenseCategory::query()->count(),
        ]);
    }
}
