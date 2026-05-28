<?php

namespace App\Services;

use App\Models\Contact;

class ContactService
{
    public function store(array $data): Contact
    {
        return Contact::create($data);
    }

    public function markAsRead(Contact $contact): void
    {
        if (! $contact->is_read) {
            $contact->update(['is_read' => true]);
        }
    }

    public function delete(Contact $contact): void
    {
        $contact->delete();
    }

    public function unreadCount(): int
    {
        return Contact::where('is_read', false)->count();
    }
}
