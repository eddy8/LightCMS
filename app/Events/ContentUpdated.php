<?php

namespace App\Events;

use App\Model\Admin\Entity;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $entity;

    /**
     * Create a new event instance.
     *
     * @param array $id
     * @param Entity $entity
     * @return void
     */
    public function __construct(array $id, Entity $entity)
    {
        $this->id = $id;
        $this->entity = $entity;
    }
}
