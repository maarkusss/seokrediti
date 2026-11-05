<?php

namespace App\Listeners;

use App\Models\Admincp\Users\UsersLogInAttempts;
use Illuminate\Auth\Events\Failed;

class LogFailedAuthenticationAttempt
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
     * @param Failed $event
     * @return void
     */
    public function handle(Failed $event)
    {
        echo '123 \n';
        $attempt = new UsersLogInAttempts();

        if ($event->user) {
            $attempt->user_id = $event->user->id;
        }

        $attempt->username = $event->credentials['username'];
        $attempt->ip_address = request()->getClientIp();
        $attempt->is_success = 0;

        $attempt->save();
    }
}
