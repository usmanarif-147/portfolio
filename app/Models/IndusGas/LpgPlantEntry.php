<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;

class LpgPlantEntry extends Model
{
    protected $table = 'indus_gas_lpg_plant_entries';

    protected $fillable = [
        'entry_date', 'rate_11_8_kg', 'rate_per_kg', 'rate_45_4_kg',
        'filled_45_4_kg_cylinders', 'filled_11_8_kg_cylinders',
        'filling_charges', 'lpg_cost', 'total_cost', 'is_paid',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'rate_11_8_kg' => 'decimal:4',
            'rate_per_kg' => 'decimal:4',
            'rate_45_4_kg' => 'decimal:4',
            'filling_charges' => 'decimal:4',
            'lpg_cost' => 'decimal:4',
            'total_cost' => 'decimal:4',
            'is_paid' => 'boolean',
        ];
    }
}
