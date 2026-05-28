<?php

namespace App\Livewire;

use App\Services\ContactService;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $message = '';

    public bool $submitted = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|max:255',
            'message' => 'required|min:10|max:2000',
        ];
    }

    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function submit(ContactService $service): void
    {
        $this->validate();

        $service->store([
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
