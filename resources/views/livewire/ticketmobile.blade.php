<div  wire:poll.3s>
    <a href="{{ route('tickets.adminIndex') }}"
       class="{{ request()->routeIs('tickets.adminIndex') ? 'active' : '' }}"
       wire:click="resetUnreadCount">
        <i class="fa-solid fa-comments fa-xl text-secondary"></i>
        @if ($unreadTicketsCount > 0)
            <span class="badge-tickets">
                {{ $unreadTicketsCount }}
            </span>
        @endif
    </a>
</div>
