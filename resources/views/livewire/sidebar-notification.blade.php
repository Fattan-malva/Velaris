<li wire:poll.2s>
    <a href="{{ route('tickets.adminIndex') }}"
       class="{{ request()->routeIs('tickets.adminIndex') ? 'active' : '' }}"
       wire:click="resetUnreadCount">
        <i class="fa-solid fa-envelope-open-text"></i>
        <span class="link_name">Tickets</span>
        @if ($unreadTicketsCount > 0)
            <span class="badge-ticket">
                {{ $unreadTicketsCount }}
            </span>
        @endif
    </a>
</li>
