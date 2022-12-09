<?php

namespace App\Services\Auth\Registration;

use App\Models\Auth\UserIdentifierType;
use App\Models\Eloquent\UserUnconfirmed;
use App\Notifications\Auth\Registration\ConfirmationEmail as NotificationEmail;
use App\Notifications\Auth\Registration\ConfirmationSms as NotificationSms;

final class NotificationSender
{
    /**
     * @throws \Exception
     */
    public function send(UserUnconfirmed $user): void
    {
        switch ($user->getIdentifierType()) {
            case UserIdentifierType::EMAIL:
                $user->notify(new NotificationEmail($user->code));
                break;
            case UserIdentifierType::PHONE:
                $user->notify(new NotificationSms($user->code));
                break;
            default:
                throw new \Exception("The sender is missing");
        }
    }
}
