<?php

namespace Bhutanio\Laravel\Contracts\Services;

interface SMSMessengerInterface
{
    /**
     * Configure the email to be sent.
     *
     * @param string $to
     * @param $message $data
     */
    public function configure($to, $message);

    /**
     * Send or queue the current email.
     */
    public function send();
}
