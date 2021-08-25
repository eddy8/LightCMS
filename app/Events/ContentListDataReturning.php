<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentListDataReturning
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Paginator $data;
    public int $entityId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $entityId, Paginator $data)
    {
        $this->data = $data;
        $this->entityId = $entityId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
