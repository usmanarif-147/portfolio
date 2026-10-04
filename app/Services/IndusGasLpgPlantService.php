<?php

namespace App\Services;

use App\Models\IndusGas\LpgPlantEntry;

class IndusGasLpgPlantService
{
    public const SMALL_CYLINDER_KG = 11.8;
    public const LARGE_CYLINDER_KG = 45.4;
    public const LARGE_FILLING_CHARGE = 10;
    public const SMALL_FILLING_CHARGE = 8;

    /** @return array<string, string|int> */
    public function calculate(string|float $rate11_8, int $largeQuantity, int $smallQuantity): array
    {
        // Monetary arithmetic uses scaled integers, then is saved to DECIMAL(14,4).
        // This deliberately avoids binary floating-point rounding in purchase totals.
        $rate11_8Units = $this->decimalToUnits($rate11_8);
        $perKgUnits = $this->roundedDivide($rate11_8Units * 10, 118); // ÷ 11.8
        // Derive the 45.4 kg price from the original plant rate, retaining the
        // full ratio rather than compounding the displayed per-KG rounding.
        $rate45_4Units = $this->roundedDivide($rate11_8Units * 454, 118); // × 45.4 ÷ 11.8
        $lpgCostUnits = ($rate45_4Units * $largeQuantity) + ($rate11_8Units * $smallQuantity);
        $fillingChargeUnits = (($largeQuantity * self::LARGE_FILLING_CHARGE) + ($smallQuantity * self::SMALL_FILLING_CHARGE)) * 10000;

        return [
            'rate_11_8_kg' => $this->unitsToDecimal($rate11_8Units),
            'rate_per_kg' => $this->unitsToDecimal($perKgUnits),
            'rate_45_4_kg' => $this->unitsToDecimal($rate45_4Units),
            'filled_45_4_kg_cylinders' => $largeQuantity,
            'filled_11_8_kg_cylinders' => $smallQuantity,
            'filling_charges' => $this->unitsToDecimal($fillingChargeUnits),
            'lpg_cost' => $this->unitsToDecimal($lpgCostUnits),
            'total_cost' => $this->unitsToDecimal($lpgCostUnits + $fillingChargeUnits),
        ];
    }

    private function decimalToUnits(string|float $value): int
    {
        $value = (string) $value;
        [$whole, $fraction] = array_pad(explode('.', $value, 2), 2, '');
        $fraction = substr(str_pad($fraction, 4, '0'), 0, 4);

        return ((int) $whole * 10000) + (int) $fraction;
    }

    private function roundedDivide(int $numerator, int $denominator): int
    {
        return intdiv($numerator + intdiv($denominator, 2), $denominator);
    }

    private function unitsToDecimal(int $units): string
    {
        return sprintf('%d.%04d', intdiv($units, 10000), $units % 10000);
    }

    /** @param array{entry_date: string, rate_11_8_kg: string, filled_45_4_kg_cylinders: string, filled_11_8_kg_cylinders: string, is_paid: bool} $data */
    public function save(LpgPlantEntry $entry, array $data): LpgPlantEntry
    {
        $calculation = $this->calculate(
            $data['rate_11_8_kg'],
            (int) $data['filled_45_4_kg_cylinders'],
            (int) $data['filled_11_8_kg_cylinders'],
        );

        $entry->fill(array_merge($calculation, [
            'entry_date' => $data['entry_date'],
            'is_paid' => $data['is_paid'],
        ]))->save();

        return $entry;
    }
}
