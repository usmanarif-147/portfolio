<?php

namespace App\Livewire\Admin\Contact;

use App\Models\Contact;
use App\Services\ContactService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class ContactIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public ?int $selectedContactId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function viewContact(ContactService $service, int $id): void
    {
        $contact = Contact::findOrFail($id);
        $service->markAsRead($contact);
        $this->selectedContactId = $id;
    }

    public function closeModal(): void
    {
        $this->selectedContactId = null;
    }

    public function delete(ContactService $service, int $id): void
    {
        $service->delete(Contact::findOrFail($id));
        session()->flash('success', 'Contact deleted successfully.');
    }

    public function render()
    {
        $query = Contact::query()->orderByDesc('created_at');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        return view('livewire.admin.contact.index', [
            'contacts' => $query->paginate(10),
            'selectedContact' => $this->selectedContactId
                ? Contact::find($this->selectedContactId)
                : null,
        ]);
    }
}
