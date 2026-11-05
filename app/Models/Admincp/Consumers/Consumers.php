<?php

namespace App\Models\Admincp\Consumers;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Consumers extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $guarded = [];
}
