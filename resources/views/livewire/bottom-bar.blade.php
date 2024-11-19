<a href="{{ route('tickets.index', session('user_id')) }}" class="nav-link" wire:poll.3s="updateUnreadMessages">
    <i class="fa-solid fa-envelope-open-text" style="font-size: 25px;"></i>
    @if($unreadMessagesCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
            style="font-size: 0.7rem; width: 18px; height: 18px;">
            {{ $unreadMessagesCount }}
        </span>
    @endif
    <span class="title-nav-bottom">Ticket</span>
</a>
