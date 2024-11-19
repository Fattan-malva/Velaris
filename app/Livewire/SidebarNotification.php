<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;

class SidebarNotification extends Component
{
    public $unreadTicketsCount;

    public function mount()
    {
        $this->unreadTicketsCount = $this->getUnreadTicketCount();
    }

    public function getUnreadTicketCount()
    {
        return Ticket::where('is_read', false)->count(); // Adjust to check boolean is_read
    }

    public function resetUnreadCount()
    {
        // Update all unread tickets to be marked as read in the database
        Ticket::where('is_read', false)->update(['is_read' => true]);

        // Reset the unread tickets count in the component
        $this->unreadTicketsCount = 0;
    }

    public function render()
    {
        $this->unreadTicketsCount = $this->getUnreadTicketCount();
        return view('livewire.sidebar-notification');
    }
}
