<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ContentDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contents;

    /**
     * Create a new event instance.
     *
     * @param Collection $contents 内容ID
     * @return void
     */
    public function __construct(Collection $contents)
    {
        $this->contents = $contents;
    }
}
