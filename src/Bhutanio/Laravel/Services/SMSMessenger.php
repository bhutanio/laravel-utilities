<?php

namespace Bhutanio\Laravel\Services;

use Nexmo\Client as Nexmo;
use Nexmo\Client\Credentials\Basic;

class SMSMessenger
{
    private $nexmo;

    public function __construct($api_key, $api_secret)
    {
        $this->nexmo = new Nexmo(new Basic($api_key, $api_secret));
    }

    public function send($from, $to, $message)
    {
        return $this->nexmo->message()->send([
            'to'   => $to,
            'from' => $from,
            'text' => $message,
        ]);
    }
}
