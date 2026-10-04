<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Staff extends Model
{
    protected $table = 'indus_gas_staff';

    protected $fillable = ['name', 'role', 'designation', 'joining_date', 'leaving_date', 'salary_package'];

    protected function casts(): array
    {
        return ['joining_date' => 'date', 'leaving_date' => 'date', 'salary_package' => 'decimal:2'];
    }

    public function expenseCategories(): BelongsToMany
    {
        return $this->belongsToMany(ExpenseCategory::class, 'indus_gas_expense_category_staff')->withTimestamps();
    }
}
