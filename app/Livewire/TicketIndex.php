<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class TicketIndex extends Component
{
    public $tickets;
    public $statusFilter = 'all'; // Default to 'all' statuses
    public $unreadMessagesCount = 0;

    // Mounting the component and loading tickets
    public function mount()
    {
        $this->loadTickets();
    }

    // Load tickets based on the status filter
    public function loadTickets()
    {
        $query = Ticket::where('user_id', session('user_id'));

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $this->tickets = $query->get();

        // Count unread messages sent by admin
        $this->updateUnreadMessagesCount();
    }

    // Update unread messages count for messages sent by admin users
    public function updateUnreadMessagesCount()
    {
        $this->unreadMessagesCount = Message::where('is_read', false)
            ->whereHas('sender', function ($query) {
                $query->where('role', 'admin'); // Assuming 'role' is a column in the 'users' table
            })
            ->whereHas('ticket', function ($query) {
                $query->where('user_id', session('user_id')); // Ensure it’s for the authenticated user
            })
            ->count();
    }

    // Filter tickets by status and reload them
    public function filterByStatus($status)
    {
        $this->statusFilter = $status;
        $this->loadTickets();
    }

    // Mark all messages in a ticket as read
    public function markMessagesAsRead($ticketId)
    {
        $ticket = Ticket::find($ticketId);

        if ($ticket) {
            $ticket->messages()->where('is_read', false)->update(['is_read' => true]);
            $this->updateUnreadMessagesCount(); // Update unread messages count after marking as read
        }

        $this->loadTickets(); // Reload tickets to reflect changes
    }

    public function render()
    {
        return view('livewire.ticket-index');
    }
}
