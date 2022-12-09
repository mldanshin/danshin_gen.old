<?php

namespace App\Notifications\Auth\Registration;

use App\Channels\SmsRu\Sender;
use App\Channels\SmsRu\Messages\NexmoMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ConfirmationSms extends Notification
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
        return [Sender::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     */
    public function toNexmo(mixed $notifiable): NexmoMessage
    {
        $message = __("auth.confirm.code") . " {$this->code}";
        return (new NexmoMessage())->content($message);
    }
}
