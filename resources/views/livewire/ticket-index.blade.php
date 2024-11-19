<div class="container-fluid" style="min-height: 100vh; display: flex; flex-direction: column; overflow-y: auto;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Your Tickets</h1>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createTicketModal">
            <i class="fa-solid fa-comment-medical"></i> Ticket
        </button>
    </div>

    <!-- Filter Buttons -->
    <div class="mb-3">
        <button wire:click="filterByStatus('all')" class="btn btn-warning" wire:loading.attr="disabled">
            All
        </button>
        <button wire:click="filterByStatus('open')" class="btn btn-success" wire:loading.attr="disabled">
            Open
        </button>
        <button wire:click="filterByStatus('in progress')" class="btn btn-primary" wire:loading.attr="disabled">
            In Progress
        </button>
        <button wire:click="filterByStatus('closed')" class="btn btn-secondary" wire:loading.attr="disabled">
            Closed
        </button>
    </div>

    <!-- Loading Spinner -->
    <div wire:loading wire:target="filterByStatus" class="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Tickets Display -->
    <div wire:poll.5s="loadTickets" style="flex-grow: 1;">
        @if($tickets->isEmpty())
            <div class="alert alert-warning" role="alert">
                Currently tickets are not available.
            </div>
        @else
            <div class="row">
                @foreach($tickets as $ticket)
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('tickets.showMobile', $ticket->id) }}" class="text-decoration-none text-dark"
                                wire:click.prevent="markMessagesAsRead({{ $ticket->id }})"
                                onclick="window.location='{{ route('tickets.showMobile', $ticket->id) }}'">
                                <div class="card ticket-container position-relative">
                                    <!-- Status Badge -->
                                    <span
                                        class="status-badge
                                            {{ $ticket->status === 'Open' ? 'open' : ($ticket->status === 'In Progress' ? 'in-progress' : 'closed') }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <strong>{{ $ticket->subject }}</strong>
                                        </h5>
                                        <p class="card-text mb-4">
                                            <small>Holder: {{ $ticket->customer->name }}</small>
                                        </p>

                                        <!-- Comment Icon with New Message Badge -->
                                        <i class="fa-solid fa-comments fa-lg position-relative">
                                            @php
                                                // Count unread messages from admins only
                                                $unreadAdminMessagesCount = $ticket->messages
                                                    ->where('is_read', false)
                                                    ->where('sender.role', 'admin')
                                                    ->count();
                                            @endphp
                                            @if($unreadAdminMessagesCount > 0)
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                                    style="font-size: 0.7rem; width: 18px; height: 18px;">
                                                    {{ $unreadAdminMessagesCount }}
                                                </span>
                                            @endif
                                        </i>
                                        <p class="card-text text-end">
                                            <small class="text-muted">{{ $ticket->created_at->format('d M Y, H:i') }}</small>
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