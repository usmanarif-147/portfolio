<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseDayClosing extends Model
{
    protected $table = 'indus_gas_expense_day_closings';

    protected $fillable = ['business_date', 'closed_by_staff_id', 'closed_at', 'notes'];

    protected function casts(): array
    {
        return ['business_date' => 'date', 'closed_at' => 'datetime'];
    }

    public function closedBy(): BelongsTo { return $this->belongsTo(Staff::class, 'closed_by_staff_id'); }
}
