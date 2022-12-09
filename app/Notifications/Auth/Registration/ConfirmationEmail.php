<?php

namespace App\Notifications\Auth\Registration;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmationEmail extends Notification
{
    use Queueable;

    public function __construct(private string $code)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return string[]
     */
    public function via(mixed $notifiable): array
    {
        return ["mail"];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(__("auth.confirm.subject"))
            ->view('auth.notification-code-email', ["code" => $this->code]);
    }
}
