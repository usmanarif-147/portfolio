<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerSettlement extends Model
{
    protected $table = 'indus_gas_partner_settlements';

    protected $fillable = ['business_date', 'paid_by_staff_id', 'received_by_staff_id', 'amount', 'payment_method', 'notes', 'settled_at'];

    protected function casts(): array
    {
        return ['business_date' => 'date', 'settled_at' => 'datetime', 'amount' => 'decimal:2'];
    }

    public function payer(): BelongsTo { return $this->belongsTo(Staff::class, 'paid_by_staff_id'); }
    public function recipient(): BelongsTo { return $this->belongsTo(Staff::class, 'received_by_staff_id'); }
}
