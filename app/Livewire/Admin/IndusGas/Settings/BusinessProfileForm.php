<?php

namespace App\Livewire\Admin\IndusGas\Settings;

use App\Models\IndusGas\BusinessProfile;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class BusinessProfileForm extends Component
{
    public string $name = 'Indus Gas';

    public string $phone = '';

    public string $email = '';

    public string $ntn = '';

    public string $address = '';

    public string $city = '';

    public string $invoice_prefix = 'IG-';

    public string $invoice_footer = 'Please count stock while receiving.';

    public function mount(): void
    {
        $profile = BusinessProfile::query()->first();

        if ($profile) {
            foreach (['name', 'phone', 'email', 'ntn', 'address', 'city', 'invoice_prefix', 'invoice_footer'] as $field) {
                $this->{$field} = $profile->{$field} ?? '';
            }
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'ntn' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'invoice_prefix' => ['required', 'string', 'max:20'],
            'invoice_footer' => ['nullable', 'string', 'max:1000'],
        ]);

        BusinessProfile::query()->firstOrNew()->fill($validated)->save();

        session()->flash('success', 'Indus Gas business profile saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.indus-gas.settings.business-profile-form');
    }
}
