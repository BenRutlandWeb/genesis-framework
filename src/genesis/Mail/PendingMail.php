<?php

namespace Genesis\Mail;

use Genesis\Mail\Mailer;

class PendingMail
{
    /**
     * The mailer instance.
     *
     * @var \Genesis\Mail\Mailer
     */
    protected $mailer;

    /**
     * The "to" recipients of the message.
     *
     * @var array
     */
    protected $to = [];

    /**
     * The "cc" recipients of the message.
     *
     * @var array
     */
    protected $cc = [];

    /**
     * The "bcc" recipients of the message.
     *
     * @var array
     */
    protected $bcc = [];

    /**
     * Create a new mailable mailer instance.
     *
     * @param  \Genesis\Mail\Mailer  $mailer
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Set the recipients of the message.
     *
     * @param  mixed  $users
     * @return self
     */
    public function to($users): self
    {
        $this->to = $users;

        return $this;
    }

    /**
     * Set the recipients of the message.
     *
     * @param  mixed  $users
     * @return self
     */
    public function cc($users): self
    {
        $this->cc = $users;

        return $this;
    }

    /**
     * Set the recipients of the message.
     *
     * @param  mixed  $users
     * @return self
     */
    public function bcc($users): self
    {
        $this->bcc = $users;

        return $this;
    }

    /**
     * Send a new mailable message instance.
     *
     * @param  \Genesis\Mail\Mailable  $mailable
     *
     * @return mixed
     */
    public function send(Mailable $mailable)
    {
        return $this->mailer->send($this->fill($mailable));
    }

    /**
     * Populate the mailable with the addresses.
     *
     * @param  \Genesis\Mail\Mailable  $mailable
     *
     * @return \Genesis\Mail\Mailable
     */
    protected function fill(Mailable $mailable): Mailable
    {
        return $mailable->to($this->to)->cc($this->cc)->bcc($this->bcc);
    }
}
