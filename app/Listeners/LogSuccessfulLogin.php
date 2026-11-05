<?php

namespace App\Listeners;

use App\Models\Admincp\Users\UsersLogInAttempts;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
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
     * @param Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        $attempt = new UsersLogInAttempts();

        $attempt->user_id = $event->user->id;
        $attempt->username = $event->user->username;
        $attempt->ip_address = request()->getClientIp();
        $attempt->is_success = 1;

        $attempt->save();
    }
}
