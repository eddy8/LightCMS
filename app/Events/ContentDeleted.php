<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;

    /**
     * Create a new event instance.
     *
     * @param array $id 内容ID
     * @return void
     */
    public function __construct(array $id)
    {
        $this->id = $id;
    }
}
