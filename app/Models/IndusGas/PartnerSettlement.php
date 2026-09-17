<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;

class PartnerSettlement extends Model
{
    protected $table = 'indus_gas_partner_settlements';

    protected $fillable = ['settlement_date', 'paid_by', 'received_by', 'amount', 'notes'];

    protected function casts(): array
    {
        return ['settlement_date' => 'date', 'amount' => 'decimal:2'];
    }
}
