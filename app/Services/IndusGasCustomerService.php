<?php

namespace App\Services;

use App\Models\IndusGas\Customer;
use Illuminate\Support\Facades\DB;

class IndusGasCustomerService
{
    public function create(array $data, array $allocations = []): Customer
    {
        return DB::transaction(function () use ($data, $allocations) {
            $customer = Customer::create($data);
            $this->syncCylinderAllocations($customer, $allocations);

            return $customer;
        });
    }

    public function update(Customer $customer, array $data, array $allocations = []): Customer
    {
        DB::transaction(function () use ($customer, $data, $allocations) {
            $customer->update($data);
            $this->syncCylinderAllocations($customer, $allocations);
        });

        return $customer;
    }

    public function delete(Customer $customer): void
    {
        $customer->delete();
    }

    private function syncCylinderAllocations(Customer $customer, array $allocations): void
    {
        $allocationData = collect($allocations)
            ->map(fn ($quantity, $cylinderTypeId) => ['cylinder_type_id' => (int) $cylinderTypeId, 'quantity' => (int) $quantity])
            ->filter(fn ($allocation) => $allocation['quantity'] > 0)
            ->values()
            ->all();

        $customer->cylinderAllocations()->delete();
        $customer->cylinderAllocations()->createMany($allocationData);
    }
}
