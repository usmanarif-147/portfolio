<?php

namespace App\Services;

use App\Models\IndusGas\Delivery;
use App\Models\IndusGas\Refill;
use Illuminate\Support\Facades\DB;

class IndusGasOperationsService
{
    public function createRefill(array $data, array $items): Refill
    {
        return DB::transaction(function () use ($data, $items) {
            $refill = Refill::create($data);
            $refill->items()->createMany($items);

            return $refill;
        });
    }

    public function createDelivery(array $data, array $items): Delivery
    {
        return DB::transaction(function () use ($data, $items) {
            $delivery = Delivery::create($data);
            $delivery->items()->createMany($items);

            return $delivery;
        });
    }
}
