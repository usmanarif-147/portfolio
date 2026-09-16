<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerCylinderAllocation extends Model
{
    protected $table = 'indus_gas_customer_cylinder_allocations';

    protected $fillable = ['cylinder_type_id', 'quantity'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function cylinderType(): BelongsTo
    {
        return $this->belongsTo(CylinderType::class);
    }
}
