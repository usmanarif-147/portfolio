<?php

namespace App\Livewire\Admin\IndusGas\Operations;

use App\Models\IndusGas\CylinderType;
use App\Models\IndusGas\Supplier;
use App\Models\IndusGas\Vehicle;
use App\Services\IndusGasOperationsService;
use App\Services\IndusGasStockService;
use Illuminate\Support\Arr;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class RefillForm extends Component
{
    public ?int $supplier_id = null;

    public ?int $vehicle_id = null;

    public string $refill_date;

    public string $lpg_rate_per_kg = '';

    public string $filled_kg = '';

    public string $filling_charge_per_cylinder = '10';

    public string $payment_method = 'online';

    public string $payment_reference = '';

    public string $notes = '';

    public array $quantities = [];

    public function mount(): void
    {
        $this->refill_date = now()->toDateString();
    }

    public function save(IndusGasOperationsService $service, IndusGasStockService $stockService): void
    {
        $data = $this->validate(['supplier_id' => ['required', 'exists:indus_gas_suppliers,id'], 'vehicle_id' => ['nullable', 'exists:indus_gas_vehicles,id'], 'refill_date' => ['required', 'date'], 'lpg_rate_per_kg' => ['required', 'numeric', 'gt:0'], 'filled_kg' => ['required', 'numeric', 'gt:0'], 'filling_charge_per_cylinder' => ['required', 'numeric', 'min:0'], 'payment_method' => ['required', 'in:online,cash,cheque'], 'payment_reference' => ['nullable', 'string', 'max:150'], 'notes' => ['nullable', 'string', 'max:2000'], 'quantities.*' => ['nullable', 'integer', 'min:0', 'max:999']]);
        $items = collect(Arr::only($this->quantities, CylinderType::query()->pluck('id')->map(fn ($id) => (string) $id)->all()))->filter(fn ($q) => (int) $q > 0)->map(fn ($q, $id) => ['cylinder_type_id' => $id, 'cylinder_quantity' => $q])->values()->all();
        if ($items === []) {
            $this->addError('quantities', 'Enter at least one cylinder quantity.');

            return;
        }
        $emptyStock = collect($stockService->warehouseStock())->mapWithKeys(fn ($row) => [$row['type']->id => $row['empty']]);
        foreach ($items as $item) {
            if ($item['cylinder_quantity'] > ($emptyStock[$item['cylinder_type_id']] ?? 0)) {
                $this->addError('quantities.'.$item['cylinder_type_id'], 'Not enough empty cylinders are recorded in warehouse stock. Add opening stock or correct the count first.');

                return;
            }
        }
        unset($data['quantities']);
        $service->createRefill($data, $items);
        session()->flash('success', 'BBN refill recorded and warehouse stock updated.');
        $this->redirectRoute('admin.indus-gas.daily-operations', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.operations.refill-form', ['suppliers' => Supplier::query()->where('is_active', true)->whereIn('supply_type', ['lpg', 'both'])->orderBy('name')->get(), 'vehicles' => Vehicle::query()->where('is_active', true)->orderBy('name')->get(), 'cylinderTypes' => CylinderType::query()->where('is_active', true)->orderByDesc('capacity_kg')->get()]);
    }
}
