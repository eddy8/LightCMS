<?php

namespace App\Listeners;

use App\Model\Admin\AdminUser;
use App\Model\Front\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateUserLastLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if ($event->guard === 'member') {
            User::query()
                ->where('id', $event->user->id)
                ->update(['last_login' => Carbon::now()]);
        } elseif ($event->guard === 'admin') {
            AdminUser::query()
                ->where('id', $event->user->id)
                ->update(['last_login' => Carbon::now()]);
        }
    }
}
