<?php

namespace App\Events;

use App\Model\Admin\Content;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $content;

    /**
     * Create a new event instance.
     *
     * @param Content $content
     * @return void
     */
    public function __construct(Content $content)
    {
        $this->content = $content;
    }
}
