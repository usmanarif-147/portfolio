<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'indus_gas_vehicles';

    protected $fillable = ['name', 'registration_number', 'vehicle_type', 'notes', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
