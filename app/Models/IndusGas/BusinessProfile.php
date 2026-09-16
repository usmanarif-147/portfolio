<?php

namespace App\Models\IndusGas;

use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    protected $table = 'indus_gas_business_profiles';

    protected $fillable = ['name', 'phone', 'email', 'ntn', 'address', 'city', 'invoice_prefix', 'invoice_footer'];
}
