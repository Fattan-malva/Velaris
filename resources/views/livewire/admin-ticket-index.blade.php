<div>
    <!-- Filter Buttons -->
    <div class="mb-3">
        <button wire:click="$set('statusFilter', 'all')"
            class="btn fw-bold {{ $statusFilter === 'all' ? 'btn-warning active-btn' : 'btn-warning' }}"
            wire:loading.attr="disabled">
            All
        </button>
        <button wire:click="$set('statusFilter', 'open')"
            class="btn fw-bold {{ $statusFilter === 'open' ? 'btn-success active-btn' : 'btn-success' }}"
            wire:loading.attr="disabled">
            Open
        </button>
        <button wire:click="$set('statusFilter', 'in progress')"
            class="btn fw-bold {{ $statusFilter === 'in progress' ? 'btn-primary active-btn' : 'btn-primary' }}"
            wire:loading.attr="disabled">
            In Progress
        </button>
        <button wire:click="$set('statusFilter', 'closed')"
            class="btn fw-bold {{ $statusFilter === 'closed' ? 'btn-secondary active-btn' : 'btn-secondary' }}"
            wire:loading.attr="disabled">
            Closed
        </button>
    </div>

    <!-- Loading Spinner - Only shown when filter is clicked -->
    <div wire:loading wire:target="statusFilter" class="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Tickets Display -->
    <div wire:poll.2s="loadTickets">
        @if($tickets->isEmpty())
            <div class="alert alert-warning" role="alert">
                Currently tickets are not available.
            </div>
        @else
            <div class="row">
                @foreach($tickets as $ticket)
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="text-decoration-none text-dark"
                                wire:click.prevent="markAsRead({{ $ticket->id }})"
                                onclick="window.location='{{ route('tickets.show', $ticket->id) }}'">
                                <div class="card ticket-container position-relative">
                                    <!-- Status Badge -->
                                    <span
                                        class="progres-badge 
                                                            {{ $ticket->status === 'Open' ? 'open' : ($ticket->status === 'In Progress' ? 'in-progress' : 'closed') }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>

                                    <div class="card-body card-ticket">
                                        <h5 class="card-title ticket-title">
                                            <strong>{{ $ticket->customer->name }}</strong>
                                        </h5>
                                        <p class="card-text mb-4 subject-ticket">{{ $ticket->subject }}</p>

                                        <!-- Comment Icon with New Message Badge -->
                                        <i class="fa-solid fa-comments fa-lg position-relative message-icon">
                                            @php
                                                // Count unread messages from admin only
                                                $unreadAdminMessagesCount = $ticket->messages
                                                    ->where('is_read', false)
                                                    ->where('sender.role', 'user') // Adjust to your role field if needed
                                                    ->count();
                                            @endphp

                                            @if ($unreadAdminMessagesCount > 0)
                                                <!-- New message notification badge from admin only -->
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-flex align-items-center justify-content-center"
                                                    style="font-size: 0.7rem; width: 18px; height: 18px;">
                                                    {{ $unreadAdminMessagesCount }}
                                                </span>
                                            @endif
                                        </i>
                                        <p class="card-text text-end">
                                            <small>{{ $ticket->created_at->format('d M Y, H:i') }}</small>
                                        </p>
                                    </div>
                                </div>
                            </a>


                        </div>
                @endforeach
            </div>
        @endif
    </div>
</div>