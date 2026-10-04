<?php

namespace App\Livewire\Admin\IndusGas\LpgPlant;

use App\Models\IndusGas\LpgPlantEntry;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class LpgPlantIndex extends Component
{
    use WithPagination;

    public string $reportFrom;
    public string $reportTo;

    public function mount(): void
    {
        $this->reportFrom = now()->startOfMonth()->toDateString();
        $this->reportTo = now()->toDateString();
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.lpg-plant.index', [
            'entries' => LpgPlantEntry::query()->orderByDesc('entry_date')->paginate(10),
        ]);
    }
}
