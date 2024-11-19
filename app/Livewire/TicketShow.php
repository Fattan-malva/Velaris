<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Message;
use App\Models\Customer;

class TicketShow extends Component
{
    public $ticket;
    public $message;
    public $ticketId;

    protected $listeners = ['messageSent' => 'reloadMessages'];

    public function mount($ticketId)
    {
        $this->ticketId = $ticketId;
        $this->loadTicket();
    }

    public function addMessage()
    {
        $this->validate([
            'message' => 'required|string',
        ]);

        // Check if the sender is an admin and update ticket status to "In Progress" if the ticket is "Open"
        if ($this->isAdmin() && $this->ticket->status == 'Open') {
            $this->ticket->status = 'In Progress';
            $this->ticket->save();
        }

        // Create a new message and set it as unread
        Message::create([
            'ticket_id' => $this->ticket->id,
            'sender_id' => session('user_id'),
            'message' => $this->message,
            'is_read' => false, // Initially unread
        ]);

        $this->message = ''; // Clear the message input

        // Dispatch event to reload messages on the page
        $this->dispatch('messageSent');
    }

    public function reloadMessages()
    {
        $this->loadTicket();
    }

    public function closeTicket()
    {
        // Close the ticket if the user is an admin
        $this->ticket->status = 'Closed';
        $this->ticket->save();
        $this->loadTicket(); // Reload ticket to reflect updated status
    }

    private function loadTicket()
    {
        // Reload ticket with associated customer and messages
        $this->ticket = Ticket::with('customer', 'messages.sender')->findOrFail($this->ticketId);

        // Optional: mark all unread messages as read, if desired
        // $this->ticket->messages()->where('is_read', false)->update(['is_read' => true]);
    }

    private function isAdmin()
    {
        // Check if the current user is an admin based on session data
        $user = Customer::find(session('user_id'));
        return $user && $user->role == 'admin'; // Assuming 'role' is a field in the User model
    }

    public function render()
    {
        return view('livewire.ticket-show');
    }
}
