<?php

namespace App\Helpers;

use App\Services\NotificationTypes;

final class Auth
{
    public static function getConfirmationInfoSender(NotificationTypes $notificationType, string $address): string
    {
        switch ($notificationType) {
            case NotificationTypes::EMAIL:
                return __("auth.confirm.email", ["address" => $address]);
            case NotificationTypes::PHONE:
                return __("auth.confirm.phone", ["address" => $address]);
            default:
                return "";
        }
    }
}
