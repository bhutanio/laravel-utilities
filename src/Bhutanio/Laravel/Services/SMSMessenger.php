<?php

namespace Bhutanio\Laravel\Services;

use Bhutanio\Laravel\Contracts\Services\SMSMessengerInterface;

class SMSMessenger implements SMSMessengerInterface
{
    protected $api_url;
    protected $api_user;
    protected $api_password;

    protected $sms_to;
    protected $sms_message;

    /**
     * SMSMessenger constructor.
     */
    public function __construct()
    {
        $this->api_url = '';
        $this->api_user = '';
        $this->api_password = '';
    }

    /**
     * Configure the email to be sent.
     *
     * @param string $to
     * @param $message $data
     */
    public function configure($to, $message)
    {
        $this->sms_to = $to;
        $this->sms_message = $message;
    }

    /**
     * Send or queue the current email.
     */
    public function send()
    {
    }
}
