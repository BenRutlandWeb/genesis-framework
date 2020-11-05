<?php

namespace Genesis\Mail;

use Genesis\Mail\Mailable;
use Genesis\Mail\PendingMail;

/**
 * The base Mailable class
 *
 * @category Theme
 * @package  TenDegrees/10degrees-base
 * @author   10 Degrees <wordpress@10degrees.uk>
 * @license  https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GPL-2.0+
 * @link     https://github.com/10degrees/10degrees-base
 * @since    2.0.0
 */
class Mailer
{
    /**
     * Begin the process of mailing a mailable class instance.
     *
     * @param  mixed  $users
     *
     * @return \Genesis\Mail\PendingMail
     */
    public function to($users)
    {
        return (new PendingMail($this))->to($users);
    }

    /**
     * Begin the process of mailing a mailable class instance.
     *
     * @param  mixed  $users
     * @return \Genesis\Mail\PendingMail
     */
    public function cc($users)
    {
        return (new PendingMail($this))->cc($users);
    }

    /**
     * Begin the process of mailing a mailable class instance.
     *
     * @param  mixed  $users
     * @return \Genesis\Mail\PendingMail
     */
    public function bcc($users)
    {
        return (new PendingMail($this))->bcc($users);
    }

    /**
     * Send the mail
     *
     * @param \Genesis\Mail\PendingMail $mailable
     *
     * @return bool
     */
    public function send(Mailable $mailable): bool
    {
        return $mailable->build()->send();
    }
}
