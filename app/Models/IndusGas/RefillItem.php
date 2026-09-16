<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefillItem extends Model
{
    protected $table = 'indus_gas_refill_items';

    protected $fillable = ['cylinder_type_id', 'cylinder_quantity'];

    public function cylinderType(): BelongsTo
    {
        return $this->belongsTo(CylinderType::class);
    }
}
