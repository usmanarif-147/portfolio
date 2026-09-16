<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'indus_gas_suppliers';

    protected $fillable = ['name', 'supply_type', 'contact_person', 'phone', 'address', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
