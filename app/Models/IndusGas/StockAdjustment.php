<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    protected $table = 'indus_gas_stock_adjustments';

    protected $fillable = ['cylinder_type_id', 'adjustment_date', 'stock_state', 'quantity_change', 'reason', 'notes'];

    protected function casts(): array
    {
        return ['adjustment_date' => 'date'];
    }

    public function cylinderType(): BelongsTo
    {
        return $this->belongsTo(CylinderType::class);
    }
}
