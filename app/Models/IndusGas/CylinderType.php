<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CylinderType extends Model
{
    protected $table = 'indus_gas_cylinder_types';

    protected $fillable = ['name', 'category', 'capacity_kg', 'is_active'];

    protected function casts(): array
    {
        return ['capacity_kg' => 'decimal:2', 'is_active' => 'boolean'];
    }

    public function customerAllocations(): HasMany
    {
        return $this->hasMany(CustomerCylinderAllocation::class);
    }
}
