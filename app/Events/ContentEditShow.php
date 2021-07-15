<?php

namespace App\Events;

use App\Foundation\ViewData;
use App\Model\Admin\Entity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentEditShow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $entity;
    public $model;
    public $viewData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Entity $entity, Model $model, ViewData $viewData)
    {
        $this->entity = $entity;
        $this->model = $model;
        $this->viewData = $viewData;
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
