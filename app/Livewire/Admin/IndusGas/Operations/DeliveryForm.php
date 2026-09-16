<?php

namespace App\Livewire\Admin\IndusGas\Operations;

use App\Models\IndusGas\Customer;
use App\Models\IndusGas\CylinderType;
use App\Models\IndusGas\Vehicle;
use App\Services\IndusGasOperationsService;
use App\Services\IndusGasStockService;
use Illuminate\Support\Arr;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class DeliveryForm extends Component
{
    public ?int $customer_id = null;

    public ?int $vehicle_id = null;

    public string $delivery_date;

    public string $sale_rate_per_kg = '';

    public string $notes = '';

    public array $delivered = [];

    public array $empty_collected = [];

    public function mount(): void
    {
        $this->delivery_date = now()->toDateString();
    }

    public function save(IndusGasOperationsService $service, IndusGasStockService $stockService): void
    {
        $data = $this->validate(['customer_id' => ['required', 'exists:indus_gas_customers,id'], 'vehicle_id' => ['nullable', 'exists:indus_gas_vehicles,id'], 'delivery_date' => ['required', 'date'], 'sale_rate_per_kg' => ['required', 'numeric', 'gt:0'], 'notes' => ['nullable', 'string', 'max:2000'], 'delivered.*' => ['nullable', 'integer', 'min:0', 'max:999'], 'empty_collected.*' => ['nullable', 'integer', 'min:0', 'max:999']]);
        $typeIds = CylinderType::query()->pluck('id')->map(fn ($id) => (string) $id)->all();
        $items = collect(Arr::only($this->delivered, $typeIds))->filter(fn ($quantity) => (int) $quantity > 0)->map(fn ($quantity, $id) => ['cylinder_type_id' => $id, 'delivered_quantity' => $quantity, 'empty_collected_quantity' => (int) ($this->empty_collected[$id] ?? $quantity)])->values()->all();
        if ($items === []) {
            $this->addError('delivered', 'Enter at least one delivered cylinder quantity.');

            return;
        }
        $fullStock = collect($stockService->warehouseStock())->mapWithKeys(fn ($row) => [$row['type']->id => $row['full']]);
        foreach ($items as $item) {
            if ($item['delivered_quantity'] > ($fullStock[$item['cylinder_type_id']] ?? 0)) {
                $this->addError('delivered.'.$item['cylinder_type_id'], 'Not enough full cylinders are recorded in warehouse stock.');

                return;
            }
        }
        unset($data['delivered'], $data['empty_collected']);
        $service->createDelivery($data, $items);
        session()->flash('success', 'Customer delivery recorded and warehouse stock updated.');
        $this->redirectRoute('admin.indus-gas.daily-operations', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.operations.delivery-form', ['customers' => Customer::query()->where('is_active', true)->orderBy('title')->get(), 'vehicles' => Vehicle::query()->where('is_active', true)->orderBy('name')->get(), 'cylinderTypes' => CylinderType::query()->where('is_active', true)->orderByDesc('capacity_kg')->get()]);
    }
}
