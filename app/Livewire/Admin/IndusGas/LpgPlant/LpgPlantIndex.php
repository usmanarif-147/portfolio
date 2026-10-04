<?php

namespace App\Livewire\Admin\IndusGas\LpgPlant;

use App\Models\IndusGas\LpgPlantEntry;
use App\Services\IndusGasLpgPlantService;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class LpgPlantIndex extends Component
{
    use WithPagination;

    public string $reportFrom;
    public string $reportTo;

    public bool $showCreateModal = false;
    public string $entry_date = '';
    public string $rate_11_8_kg = '';
    public string $filled_45_4_kg_cylinders = '0';
    public string $filled_11_8_kg_cylinders = '0';
    public bool $is_paid = false;

    public function mount(): void
    {
        $this->reportFrom = now()->startOfMonth()->toDateString();
        $this->reportTo = now()->toDateString();
        $this->resetEntryForm();
    }

    public function openCreateModal(): void
    {
        $this->resetEntryForm();
        $this->resetValidation();
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetValidation();
    }

    public function save(IndusGasLpgPlantService $service): void
    {
        $data = $this->validate([
            'entry_date' => [
                'bail', 'required', 'date', Rule::unique('indus_gas_lpg_plant_entries', 'entry_date'),
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
        $service->save(new LpgPlantEntry(), $data);

        $this->showCreateModal = false;
        $this->resetEntryForm();
        $this->resetPage();
        session()->flash('success', 'LPG Plant entry saved successfully.');
    }

    private function resetEntryForm(): void
    {
        $this->entry_date = now()->toDateString();
        $this->rate_11_8_kg = '';
        $this->filled_45_4_kg_cylinders = '0';
        $this->filled_11_8_kg_cylinders = '0';
        $this->is_paid = false;
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

        return view('livewire.admin.indus-gas.lpg-plant.index', [
            'entries' => LpgPlantEntry::query()->orderByDesc('entry_date')->paginate(10),
            'preview' => $preview,
        ]);
    }
}
