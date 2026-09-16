<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryItem extends Model
{
    protected $table = 'indus_gas_delivery_items';

    protected $fillable = ['cylinder_type_id', 'delivered_quantity', 'empty_collected_quantity'];

    public function cylinderType(): BelongsTo
    {
        return $this->belongsTo(CylinderType::class);
    }
}
