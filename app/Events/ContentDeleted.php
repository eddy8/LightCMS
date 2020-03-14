<?php

namespace App\Events;

use App\Model\Admin\Entity;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ContentDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contents;
    public $entity;

    /**
     * Create a new event instance.
     *
     * @param Collection $contents
     * @param Entity $entity
     * @return void
     */
    public function __construct(Collection $contents, $entity)
    {
        $this->contents = $contents;
        $this->entity = $entity;
    }
}
