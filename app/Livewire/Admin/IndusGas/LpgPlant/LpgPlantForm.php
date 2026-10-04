<?php

namespace App\Livewire\Admin\IndusGas\LpgPlant;

use App\Models\IndusGas\LpgPlantEntry;
use App\Services\IndusGasLpgPlantService;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class LpgPlantForm extends Component
{
    public LpgPlantEntry $entry;
    public string $entry_date = '';
    public string $rate_11_8_kg = '';
    public string $filled_45_4_kg_cylinders = '0';
    public string $filled_11_8_kg_cylinders = '0';
    public bool $is_paid = false;

    public function mount(LpgPlantEntry $entry): void
    {
        $this->entry = $entry;
        $this->entry_date = $entry->entry_date->toDateString();
        $this->rate_11_8_kg = (string) $entry->rate_11_8_kg;
        $this->filled_45_4_kg_cylinders = (string) $entry->filled_45_4_kg_cylinders;
        $this->filled_11_8_kg_cylinders = (string) $entry->filled_11_8_kg_cylinders;
        $this->is_paid = $entry->is_paid;
    }

    public function save(IndusGasLpgPlantService $service): void
    {
        $data = $this->validate([
            'entry_date' => [
                'bail', 'required', 'date',
                Rule::unique('indus_gas_lpg_plant_entries', 'entry_date')->ignore($this->entry->id),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (Carbon::parse($value)->isSunday()) {
                        $fail('Sunday is an off day; LPG Plant entries cannot be recorded for Sunday.');
                    }
                },
            ],
            'rate_11_8_kg' => ['required', 'numeric', 'gt:0', 'decimal:0,4'],
            'filled_45_4_kg_cylinders' => ['required', 'integer', 'min:0', 'max:9999'],
            'filled_11_8_kg_cylinders' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_paid' => ['boolean'],
        ]);

        $data['filled_11_8_kg_cylinders'] = $data['filled_11_8_kg_cylinders'] ?: '0';
        $service->save($this->entry, $data);

        session()->flash('success', 'LPG Plant entry saved successfully.');
        $this->redirectRoute('admin.indus-gas.lpg-plant', navigate: true);
    }

    public function render(IndusGasLpgPlantService $service)
    {
        $preview = null;
        if (is_numeric($this->rate_11_8_kg) && (float) $this->rate_11_8_kg > 0) {
            $preview = $service->calculate(
                $this->rate_11_8_kg,
                max(0, (int) $this->filled_45_4_kg_cylinders),
                max(0, (int) $this->filled_11_8_kg_cylinders),
            );
        }

        return view('livewire.admin.indus-gas.lpg-plant.form', compact('preview'));
    }
}
