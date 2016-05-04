<?php

namespace Bhutanio\Laravel\Services;

use Bhutanio\Laravel\Contracts\Services\EmailerInterface;
use Illuminate\Mail\Mailer;

class Emailer implements EmailerInterface
{
    /**
     * @var Mailer
     */
    protected $mailer;

    protected $to;

    protected $subject;

    protected $attachment;

    protected $view_html;

    protected $view_plain;

    protected $data = [];

    /**
     * Emailer constructor.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Configure the email to be sent.
     *
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array $data
     *
     * @return self
     */
    public function configure($to, $subject, $view, $data)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->view_html = $view . '-html';
        $this->view_plain = $view . '-plain';

        $this->data = array_merge(
            [
                'to' => $to,
                'subject' => $subject,
            ],
            $data
        );

        return $this;
    }

    /**
     * Send or queue the current email.
     */
    public function send()
    {
        $this->mailer->send(
            [
                $this->view_html,
                $this->view_plain,
            ],
            $this->data,
            function (\Illuminate\Mail\Message $message) {
                $message->to($this->to);
                $message->subject($this->subject);
                if ($this->attachment) {
                    $message->attach($this->attachment);
                }
            }
        );
    }
}
