<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Refill extends Model
{
    protected $table = 'indus_gas_refills';

    protected $fillable = ['supplier_id', 'vehicle_id', 'refill_date', 'lpg_rate_per_kg', 'filled_kg', 'filling_charge_per_cylinder', 'payment_method', 'payment_reference', 'notes'];

    protected function casts(): array
    {
        return ['refill_date' => 'date', 'lpg_rate_per_kg' => 'decimal:2', 'filled_kg' => 'decimal:2', 'filling_charge_per_cylinder' => 'decimal:2'];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RefillItem::class);
    }
}
