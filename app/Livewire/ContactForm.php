<?php

namespace App\Livewire;

use App\Services\ContactService;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ContactForm extends Component
{
    private const GENERIC_ERROR = 'Something went wrong. Please try again later.';

    private const RATE_LIMIT_MAX = 3;

    private const RATE_LIMIT_DECAY = 3600;

    private const MIN_FILL_SECONDS = 3;

    public string $name = '';

    public string $email = '';

    public string $message = '';

    public string $website = '';

    public int $loadedAt = 0;

    public bool $submitted = false;

    public function mount(): void
    {
        $this->loadedAt = now()->timestamp;
    }

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

        if ($this->website !== '') {
            $this->addError('form', self::GENERIC_ERROR);

            return;
        }

        if (now()->timestamp - $this->loadedAt < self::MIN_FILL_SECONDS) {
            $this->addError('form', self::GENERIC_ERROR);

            return;
        }

        $key = 'contact-form:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, self::RATE_LIMIT_MAX)) {
            $this->addError('form', self::GENERIC_ERROR);

            return;
        }

        RateLimiter::hit($key, self::RATE_LIMIT_DECAY);

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
