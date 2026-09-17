<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;

class CashAdvance extends Model
{
    protected $table = 'indus_gas_cash_advances';

    protected $fillable = ['advance_date', 'given_by', 'amount', 'notes'];

    protected function casts(): array
    {
        return ['advance_date' => 'date', 'amount' => 'decimal:2'];
    }
}
