<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'indus_gas_customers';

    protected $fillable = [
        'title',
        'location',
        'phone',
        'type',
    ];
}
