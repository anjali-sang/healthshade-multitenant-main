<?php

namespace App\Notifications;

use Illuminate\Notifications\Notifiable;

class SystemNotifier
{
    use Notifiable;

    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }
}
