<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invoice extends Model
{
    protected $table = 'indus_gas_invoices';

    protected $fillable = ['delivery_id', 'customer_id', 'invoice_number', 'invoice_date', 'total_kg', 'rate_per_kg', 'total_amount', 'notes'];

    protected function casts(): array
    {
        return ['invoice_date' => 'date', 'total_kg' => 'decimal:2', 'rate_per_kg' => 'decimal:2', 'total_amount' => 'decimal:2'];
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'indus_gas_payment_invoice')->withPivot('amount')->withTimestamps();
    }
}
