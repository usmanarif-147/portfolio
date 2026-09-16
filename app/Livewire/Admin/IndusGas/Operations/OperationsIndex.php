<?php

namespace App\Livewire\Admin\IndusGas\Operations;

use App\Models\IndusGas\Delivery;
use App\Models\IndusGas\Refill;
use App\Services\IndusGasStockService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class OperationsIndex extends Component
{
    public function render(IndusGasStockService $stockService)
    {
        return view('livewire.admin.indus-gas.operations.index', ['refills' => Refill::query()->with('supplier')->latest('refill_date')->limit(5)->get(), 'deliveries' => Delivery::query()->with('customer')->latest('delivery_date')->limit(5)->get(), 'stock' => $stockService->warehouseStock()]);
    }
}
