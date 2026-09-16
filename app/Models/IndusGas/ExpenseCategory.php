<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $table = 'indus_gas_expense_categories';

    protected $fillable = ['name', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
