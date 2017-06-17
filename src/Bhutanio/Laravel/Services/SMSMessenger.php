<?php

namespace Bhutanio\Laravel\Services;

use Nexmo\Client as Nexmo;
use Nexmo\Client\Credentials\Basic;
use Nexmo\Message\Message;
use Nexmo\Message\Text;

class SMSMessenger
{
    private $nexmo;

    private $from;

    private $url;

    /**
     * Nexmo SMS Messenger.
     *
     * @param string $api_key - Nexmo API Key
     * @param string $api_secret - Nexmo API Secret
     * @param null|string $from
     * @param null|string $callback_url - URL to send callback response
     */
    public function __construct($api_key, $api_secret, $from, $callback_url = null)
    {
        $this->from = $from;
        $this->url = $callback_url;

        $this->nexmo = new Nexmo(new Basic($api_key, $api_secret));
    }

    /**
     * Send SMS Message
     *
     * @param int $to
     * @param string $message
     * @param null|string $from
     * @return Message
     */
    public function send($to, $message, $from = null)
    {
        if (empty($from)) {
            $from = $this->from;
        }

        $additional = [];
        if ($this->url && filter_var($this->url, FILTER_VALIDATE_URL)) {
            $additional['callback'] = $this->url;
        }

        $text = new Text($to, $from, $message, $additional);

        return $this->nexmo->message()->send($text);
    }
}
