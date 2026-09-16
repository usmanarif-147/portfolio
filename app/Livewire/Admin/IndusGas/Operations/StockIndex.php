<?php

namespace App\Livewire\Admin\IndusGas\Operations;

use App\Models\IndusGas\CylinderType;
use App\Models\IndusGas\StockAdjustment;
use App\Services\IndusGasStockService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class StockIndex extends Component
{
    public ?int $cylinder_type_id = null;

    public string $adjustment_date;

    public string $stock_state = 'full';

    public string $quantity_change = '';

    public string $reason = '';

    public string $notes = '';

    public function mount(): void
    {
        $this->adjustment_date = now()->toDateString();
    }

    public function saveAdjustment(): void
    {
        $data = $this->validate(['cylinder_type_id' => ['required', 'exists:indus_gas_cylinder_types,id'], 'adjustment_date' => ['required', 'date'], 'stock_state' => ['required', 'in:full,empty'], 'quantity_change' => ['required', 'integer', 'between:-999,999', 'not_in:0'], 'reason' => ['required', 'string', 'max:150'], 'notes' => ['nullable', 'string', 'max:1000']]);
        StockAdjustment::create($data);
        session()->flash('success', 'Stock adjustment recorded.');
        $this->reset(['cylinder_type_id', 'quantity_change', 'reason', 'notes']);
        $this->stock_state = 'full';
        $this->adjustment_date = now()->toDateString();
    }

    public function render(IndusGasStockService $stockService)
    {
        return view('livewire.admin.indus-gas.operations.stock-index', ['stock' => $stockService->warehouseStock(), 'cylinderTypes' => CylinderType::query()->where('is_active', true)->orderByDesc('capacity_kg')->get(), 'adjustments' => StockAdjustment::query()->with('cylinderType')->latest('adjustment_date')->latest('id')->limit(12)->get()]);
    }
}
