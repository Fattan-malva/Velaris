<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Message;

class AdminTicketIndex extends Component
{
    public $tickets;
    public $newTicketCount = 0;  // Define the property for unread ticket count
    public $statusFilter = 'all';  // Property to store selected ticket status filter

    public function mount()
    {
        $this->loadTickets(); // Load tickets when the component is mounted
    }

    public function loadTickets()
    {
        // Fetch tickets with associated customer and messages, applying the status filter
        $query = Ticket::with('customer', 'messages');
        
        // Apply status filter if it's not 'all'
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Get the filtered tickets
        $this->tickets = $query->get();

        // Count new tickets that have unread messages
        $this->newTicketCount = $this->tickets->filter(function ($ticket) {
            return $ticket->messages->where('is_read', false)->count() > 0;
        })->count();
    }

    public function markAsRead($ticketId)
    {
        // Find the ticket and update its unread messages
        $ticket = Ticket::find($ticketId);
    
        if ($ticket) {
            // Update all unread messages associated with this ticket to is_read = true
            Message::where('ticket_id', $ticketId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
    
            // Reload the tickets to reflect changes
            $this->loadTickets();
        }
    }
    

    public function render()
    {
        // Pass unread ticket count and tickets to the view
        return view('livewire.admin-ticket-index', [
            'newTicketCount' => $this->newTicketCount,  // Pass the count to the view
        ]);
    }
}
