<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;

class TicketCreate extends Component
{
    public $subject;

    protected $rules = [
        'subject' => 'required|string|max:255',
    ];

    public function createTicket()
    {
        $this->validate();

        Ticket::create([
            'subject' => $this->subject,
            'user_id' => session('user_id'),
        ]);

        // Reset the subject field for a clean form
        $this->reset('subject');

        // Emit events to close the modal and refresh the ticket list
        $this->dispatch('ticketCreated'); // For refreshing the index
        $this->dispatch('closeCreateTicketModal'); // For closing the modal

        session()->flash('message', 'Ticket created successfully.');
    }

    public function render()
    {
        return view('livewire.ticket-create');
    }
}
