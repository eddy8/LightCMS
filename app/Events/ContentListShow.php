<?php

namespace App\Events;

use App\Model\Admin\Content;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentListShow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $entityId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $entityId)
    {
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
