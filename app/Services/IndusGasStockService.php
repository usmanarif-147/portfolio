<?php

namespace App\Services;

use App\Models\IndusGas\CylinderType;
use App\Models\IndusGas\DeliveryItem;
use App\Models\IndusGas\RefillItem;
use App\Models\IndusGas\StockAdjustment;

class IndusGasStockService
{
    public function warehouseStock(): array
    {
        return CylinderType::query()->orderByDesc('capacity_kg')->get()->map(function (CylinderType $type) {
            $refilled = (int) RefillItem::query()->where('cylinder_type_id', $type->id)->sum('cylinder_quantity');
            $delivered = (int) DeliveryItem::query()->where('cylinder_type_id', $type->id)->sum('delivered_quantity');
            $emptiesCollected = (int) DeliveryItem::query()->where('cylinder_type_id', $type->id)->sum('empty_collected_quantity');
            $fullAdjustments = (int) StockAdjustment::query()->where('cylinder_type_id', $type->id)->where('stock_state', 'full')->sum('quantity_change');
            $emptyAdjustments = (int) StockAdjustment::query()->where('cylinder_type_id', $type->id)->where('stock_state', 'empty')->sum('quantity_change');

            return ['type' => $type, 'full' => $fullAdjustments + $refilled - $delivered, 'empty' => $emptyAdjustments + $emptiesCollected - $refilled, 'allocated' => (int) $type->customerAllocations()->sum('quantity')];
        })->all();
    }
}
