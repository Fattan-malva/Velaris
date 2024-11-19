@extends('layouts.app')

@section('content')
    <br><br><br><br>
    <!-- Modal -->
    <div class="modal fade" id="createTicketModal" tabindex="-1" aria-labelledby="createTicketModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-center" id="createTicketModalLabel">Create New Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- TicketCreate Livewire Component -->
                    @livewire('ticket-create')
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Index Component -->
    @livewire('ticket-index')
@endsection


<style>
    /* Card and badge styling */
.ticket-container {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    background-color: #fff;
    transition: transform 0.2s;
}

.ticket-container:hover {
    transform: translateY(-4px);
}

.status-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 10px;
    border-radius: 15px;
    color: #fff;
    font-size: 0.8rem;
    font-weight: bold;
    text-transform: uppercase;
}

.status-badge.open {
    background-color: #28a745;
}

.status-badge.in-progress {
    background-color: #007bff;
}

.status-badge.closed {
    background-color: #6c757d;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .ticket-container {
        margin: 0px 0;
    }
    .row {
        margin-left: 0;
        margin-right: 0;
    }
    .card-body {
        padding: 10px;
    }
    .card-title {
        font-size: 1rem;
    }
    .status-badge {
        font-size: 0.7rem;
    }
}

</style>
<script>
    document.addEventListener('livewire:load', function () {
        // Listen for the 'closeCreateTicketModal' event to hide the modal
        Livewire.on('closeCreateTicketModal', () => {
            var createTicketModal = bootstrap.Modal.getInstance(document.getElementById('createTicketModal'));
            createTicketModal.hide();
        });
    });
</script>
