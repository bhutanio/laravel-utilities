<?php

namespace Bhutanio\Laravel\Services;

use Nexmo\Client as Nexmo;
use Nexmo\Client\Credentials\Basic;

class SMSMessenger
{
    private $nexmo;

    /**
     * Nexmo SMS Messenger.
     *
     * @param string $api_key Nexmo API Key
     * @param string $api_secret Nexmo API Secret
     */
    public function __construct($api_key, $api_secret)
    {
        $this->nexmo = new Nexmo(new Basic($api_key, $api_secret));
    }

    /**
     * Send SMS Message
     *
     * @param $from
     * @param $to
     * @param $message
     * @return \Nexmo\Message\Message
     */
    public function send($from, $to, $message)
    {
        return $this->nexmo->message()->send([
            'to'   => $to,
            'from' => $from,
            'text' => $message,
        ]);
    }
}
