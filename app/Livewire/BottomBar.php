<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;

class BottomBar extends Component
{
    public $unreadMessagesCount = 0;

    protected $listeners = ['refreshUnreadMessages'];

    public function mount()
    {
        $this->updateUnreadMessages();
    }

    // Polling method for updating unread messages count, only from admin
    public function updateUnreadMessages()
    {
        $this->unreadMessagesCount = Message::where('is_read', false)
            ->whereHas('sender', function ($query) {
                $query->where('role', 'admin'); // Assumes 'role' is a column in the users table
            })
            ->count();
    }

    // Method to refresh the count via an external trigger
    public function refreshUnreadMessages()
    {
        $this->updateUnreadMessages();
    }

    public function render()
    {
        return view('livewire.bottom-bar');
    }
}
