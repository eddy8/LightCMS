<?php

namespace App\Events;

use App\Model\Admin\Content;
use App\Model\Admin\Entity;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $content;
    public $entity;

    /**
     * Create a new event instance.
     *
     * @param Content $content
     * @param Entity $entity
     * @return void
     */
    public function __construct(Content $content, Entity $entity)
    {
        $this->content = $content;
        $this->entity = $entity;
    }
}
