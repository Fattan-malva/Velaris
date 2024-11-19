<div wire:poll.2s class="chat-container">
    <div class="custom-card-header">
        <!-- Custom header content -->
        <div class="header-left">
            <div class="user-info">
                <img src="{{ asset('assets/img/admin.png') }}" alt="User Icon" class="user-icon">
                <h1 class="chat-header">{{ $ticket->customer->name }}</h1>
            </div>
        </div>
        <div class="header-right">
            @if($ticket->status !== 'Closed' && session('user_id') === $ticket->customer->id)
                <!-- Only show this badge for customers who can close the ticket -->
                <span wire:click="closeTicket" class="badge badge-danger cursor-pointer">Close Ticket</span>
            @elseif($ticket->status !== 'Closed' && $this->isAdmin())
                <!-- Admin can close the ticket -->
                <span wire:click="closeTicket" class="badge badge-danger cursor-pointer">Close Ticket</span>
            @endif

        </div>
    </div>

    <!-- Scrollable Chat Messages Section -->
    <div class="chat-messages">
        <ul class="chat-message-list">
            @php
                $shownDates = [];
            @endphp
            @foreach($ticket->messages as $message)
                        @php
                            $messageDate = $message->created_at->format('Y-m-d');
                            $badgeLabel = '';

                            // Determine the badge label based on the message date
                            if ($messageDate == now()->format('Y-m-d')) {
                                $badgeLabel = 'Today';
                            } elseif ($messageDate == now()->subDay()->format('Y-m-d')) {
                                $badgeLabel = 'Yesterday';
                            } else {
                                $badgeLabel = $message->created_at->format('F j, Y');
                            }
                        @endphp

                        <!-- Show a new date badge only when the date changes -->
                        @if (!in_array($messageDate, $shownDates))
                                <div class="date-badge">{{ $badgeLabel }}</div>
                                @php
                                    $shownDates[] = $messageDate;
                                @endphp
                        @endif

                        <li class="chat-message-item {{ $message->sender_id === session('user_id') ? 'self' : 'other' }}">
                            <div class="message-bubble {{ $message->sender_id === session('user_id') ? 'self' : 'other' }}">
                                <p class="message-text">{{ $message->message }}</p>
                                <small class="message-time">{{ $message->created_at->format('H:i') }}</small>
                            </div>
                        </li>
            @endforeach
        </ul>

        <!-- Keterangan Ticket Closed -->
        @if($ticket->status === 'Closed')
            <div class="ticket-closed-notification">
                <p><strong>The ticket has been closed.</strong></p>
            </div>
        @endif
    </div>

    <!-- Input Form Section -->
    <div class="chat-input-container">
        <!-- Disable the input if the ticket is closed -->
        @if($ticket->status !== 'Closed')
            <form wire:submit.prevent="addMessage" style="display: flex; gap: 10px;">
                <input wire:model="message" rows="2" class="chat-textarea" placeholder="Type a message" required></input>
                <button type="submit" class="chat-submit-button">
                    &#10148; <!-- Arrow icon -->
                </button>
            </form>
        @else
            <!-- Show a message saying the user cannot send messages -->
            <div class="chat-message-notice">
                <form wire:submit.prevent="addMessage" style="display: flex; gap: 10px;">
                    <input wire:model="message" rows="2" class="chat-textarea"
                        placeholder="You cannot send messages because this ticket is closed." readonly></input>
                    <button type="submit" class="chat-submit-button">
                        &#10148; <!-- Arrow icon -->
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>