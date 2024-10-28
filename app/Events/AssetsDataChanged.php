<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Assets;



class AssetsDataChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $asset;
    public $action;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Assets $asset
     * @param string $action
     */
    public function __construct(Assets $asset, $action)
    {
        $this->asset = $asset;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('assets');
    }

public function broadcastWith()
{
    return [
        'id' => $this->asset->id,
        'tagging' => optional($this->asset->inventory)->tagging ?? 'Unknown', 
        'jenis_aset' => $this->asset->jenis_aset,
        'merk' => optional($this->asset->merk)->name ?? 'Unknown', // Access the merk name
        'nama' => optional($this->asset->customer)->name ?? 'Unknown', 
        'approval_status' => $this->asset->approval_status,
        'aksi' => $this->asset->aksi,
        'action' => $this->action, // created, updated, or deleted
    ];
}

    
}
