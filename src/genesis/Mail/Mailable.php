<?php

namespace Genesis\Mail;

use Closure;
use ReflectionClass;
use ReflectionProperty;
use Genesis\Database\Models\User;
use Genesis\Support\Facades\Event;
use Illuminate\Support\Collection;
use PHPMailer\PHPMailer\PHPMailer;
use WP_User;

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
abstract class Mailable
{
    /**
     * The email addresses
     *
     * @var array
     */
    protected $to = [];

    /**
     * The email subject
     *
     * @var string
     */
    protected $subject = '';

    /**
     * The email html body
     *
     * @var string
     */
    protected $html = '';

    /**
     * The email plain text body
     *
     * @var string
     */
    protected $plainText = '';

    /**
     * The headers to send with the email
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Any email attachments
     *
     * @var array
     */
    protected $attachments = [];

    /**
     * Set the recipients
     *
     * @param mixed $user The user or email address
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function to($addresses): Mailable
    {
        $this->resolveEmailAddresses($addresses, function ($email) {
            $this->to[] = $email;
        });

        return $this;
    }

    /**
     * Set the Cc header
     *
     * @param mixed $user The user or email address
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function cc($addresses): Mailable
    {
        return $this->resolveEmailAddresses($addresses, function ($email) {
            return $this->header("Cc: {$email}");
        });
    }

    /**
     * Set the Bcc header
     *
     * @param mixed $user The user or email address
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function bcc($addresses): Mailable
    {
        return $this->resolveEmailAddresses($addresses, function ($email) {
            return $this->header("Bcc: {$email}");
        });
    }

    /**
     * Set the From and Reply-To headers
     *
     * @param mixed $user The user or email address
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function from($address): Mailable
    {
        $email = $this->resolveEmailAddress($address);

        return $this->header("From: {$email}")->header("Reply-To: {$email}");
    }

    /**
     * Set the email subject
     *
     * @param string $subject The email subject
     *
     * @return void
     */
    public function subject(string $subject): Mailable
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set the email message
     *
     * @param string $path The view path
     * @param array  $data The view data
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function text(string $path, array $data = []): Mailable
    {
        $this->plainText = (string) view($path, $this->buildViewData($data));

        return $this;
    }

    /**
     * Set the email message with a view
     *
     * @param string $path The view path
     * @param array  $data The view data
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function view(string $path, array $data = []): Mailable
    {
        $this->html = (string) view($path, $this->buildViewData($data));

        return $this;
    }

    /**
     * Set the email message with a markdown view
     *
     * @param string $path The view path
     * @param array  $data The view data
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function markdown(string $path, array $data = []): Mailable
    {
        $this->text($path, $data);

        $this->html = app('markdown')->text($this->plainText);

        return $this;
    }

    /**
     * Resolve the email address from a user
     *
     * @param \Genesis\Database\Models\User|\WP_User|string $user The user to resolve
     *
     * @return void
     */
    protected function resolveEmailAddress($address)
    {
        if ($address instanceof User) {
            return $this->formatEmailAddress(
                $address->email,
                $address->name ?? ''
            );
        }
        if ($address instanceof WP_User) {
            return $this->formatEmailAddress(
                $address->user_email,
                $address->display_name ?? ''
            );
        }
        return $address;
    }

    /**
     * Resolve an array or single email address and run the given callback
     *
     * @param array|string $addresses
     * @param \Closure     $callback
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    protected function resolveEmailAddresses($addresses, Closure $callback): Mailable
    {
        if (!is_array($addresses) && !$addresses instanceof Collection) {
            $addresses = [$addresses];
        }

        foreach ($addresses as $address) {
            $callback($this->resolveEmailAddress($address));
        }

        return $this;
    }

    /**
     * Return a formatted email string
     *
     * @param string $email The user email
     * @param string $name  The user name
     *
     * @return string
     */
    protected function formatEmailAddress(string $email, string $name = ''): string
    {
        return "{$name} <{$email}>";
    }

    /**
     * Merge the data passed to the view with the instance public properties.
     *
     * @param array $data
     *
     * @return array
     */
    protected function buildViewData(array $data): array
    {
        $properties = [];

        foreach ((new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->getDeclaringClass()->getName() !== self::class) {
                $properties[$property->getName()] = $property->getValue($this);
            }
        }
        return array_merge($properties, $data);
    }

    /**
     * Set a header
     *
     * @param string $header The header to set
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function header(string $header): Mailable
    {
        $this->headers[] = $header;

        return $this;
    }

    /**
     * Set multiple headers
     *
     * @param array $headers The headers to set
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function headers(array $headers): Mailable
    {
        foreach ($headers as $header) {
            $this->header($header);
        }

        return $this;
    }

    /**
     * Set an attachment to the email
     *
     * @param string $filepath The attachment filepath
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function attachment(string $filepath): Mailable
    {
        $this->attachments[] = $filepath;

        return $this;
    }

    /**
     * Set attachments to the email
     *
     * @param array $filepaths The attachment filepaths
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function attachments(array $filepaths): Mailable
    {
        foreach ($filepaths as $filepath) {
            $this->attachment($filepath);
        }

        return $this;
    }

    /**
     * Build the email
     *
     * @return \Genesis\Support\Mail\Mailable
     */
    public function build(): Mailable
    {
        return $this;
    }

    /**
     * Get the message body
     *
     * @return string
     */
    protected function message(): string
    {
        if ($this->html) {
            Event::listen('phpmailer_init', function (PHPMailer $mailer) {
                $mailer->AltBody = $this->plainText;
            });
            $this->header('Content-Type: text/html; charset=UTF-8');

            return view('emails.message', ['slot' => $this->html]);
        }
        return $this->plainText;
    }

    /**
     * Build and send an email
     *
     * @return boolean
     */
    public function send(): bool
    {
        return wp_mail(
            $this->to,
            $this->subject,
            $this->message(),
            $this->headers,
            $this->attachments
        );
    }

    /**
     * Render the email
     *
     * @return string
     */
    public function render(): string
    {
        $this->build();

        return $this->html;
    }

    /**
     * Return the email render
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
