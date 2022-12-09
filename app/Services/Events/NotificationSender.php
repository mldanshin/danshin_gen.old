<?php

namespace App\Services\Events;

use App\Models\Eloquent\User;
use App\Models\Events\Events;
use App\Notifications\Events\Events as Notification;
use App\Services\NotificationTypes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;

final class NotificationSender
{
    /**
     * @param Collection|User[] $users
     */
    public function __construct(
        private NotificationTypes $senderType,
        private Events $events,
        private Collection $users
    ) {
    }

    /**
     * @throws \Exception
     */
    public function send(): bool
    {
        $success = true;

        switch ($this->senderType) {
            case NotificationTypes::TELEGRAM:
                foreach ($this->users as $user) {
                    try {
                        if (config("app.env") !== "testing") {
                            $user->notify(new Notification($this->events));
                        }
                    } catch (CouldNotSendNotification $e) {
                        Log::error("idUser = {$user->id};   " . $e->__toString());
                        $success = false;
                    }
                }
                break;
            default:
                throw new \Exception("The sender is missing");
        }

        return $success;
    }
}
