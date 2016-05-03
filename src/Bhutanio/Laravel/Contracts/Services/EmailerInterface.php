<?php

namespace Bhutanio\Laravel\Contracts\Services;

interface EmailerInterface
{
    /**
     * Configure the email to be sent.
     *
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array  $data
     *
     * @return self
     */
    public function configure($to, $subject, $view, $data);

    /**
     * Send or queue the current email.
     */
    public function send();
}
