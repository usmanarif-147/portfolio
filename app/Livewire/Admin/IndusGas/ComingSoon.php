<?php

namespace App\Livewire\Admin\IndusGas;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ComingSoon extends Component
{
    public string $feature;

    public function mount(string $feature): void
    {
        $this->feature = $feature;
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.coming-soon');
    }
}
