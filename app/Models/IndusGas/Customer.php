<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'indus_gas_customers';

    protected $fillable = [
        'title',
        'location',
        'phone',
        'type',
        'contact_person',
        'email',
        'city',
        'billing_address',
        'ntn',
        'payment_term',
        'payment_due_days',
        'whatsapp_group_name',
        'whatsapp_group_url',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function cylinderAllocations(): HasMany
    {
        return $this->hasMany(CustomerCylinderAllocation::class);
    }
}
