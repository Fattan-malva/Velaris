<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Customer;

class CustomerDataChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;
    public $action;  // to specify what type of action took place (create, update, delete)

    /**
     * Create a new event instance.
     *
     * @param Customer $customer
     * @param string $action
     */
    public function __construct(Customer $customer, string $action)
    {
        $this->customer = $customer;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [new Channel('customers')];  // You can choose any channel name
    }

    /**
     * Broadcast with this data.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'customer' => $this->customer,
            'action' => $this->action,  // This will let the frontend know what type of update occurred
        ];
    }
}
