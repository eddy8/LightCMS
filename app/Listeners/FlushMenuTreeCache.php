<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;

class FlushMenuTreeCache
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        Cache::forget('menu:tree');
    }
}
