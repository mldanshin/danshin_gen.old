<?php

namespace App\Services\Auth\Registration;

use App\Models\Auth\UserIdentifierType;
use App\Models\Auth\Registration\FormRequest;
use App\Models\Auth\Registration\NotificationInfo;
use App\Models\Eloquent\UserUnconfirmed;
use App\Repositories\Auth\Registration\Registration as Repository;
use App\Services\NotificationTypes;

final class Registration
{
    public function __construct(private Repository $repository)
    {
    }

    public function sendFirstConfirmationCode(FormRequest $request): NotificationInfo
    {
        $user = $this->repository->createUserUnconfirmed($request);
        $this->sendConfirmationCode($user);
        $user->save();

        return new NotificationInfo(
            $user->id,
            $this->convertUserIdentifierTypeToNotificationType($user->getIdentifierType()),
            $user->identifier
        );
    }

    public function sendRepeatConfirmationCode(string $idUser): NotificationInfo
    {
        $user = $this->repository->repeatUserUnconfirmed($idUser);
        $this->sendConfirmationCode($user);
        $user->save();

        return new NotificationInfo(
            $user->id,
            $this->convertUserIdentifierTypeToNotificationType($user->getIdentifierType()),
            $user->identifier
        );
    }

    private function sendConfirmationCode(UserUnconfirmed $user): void
    {
        (new NotificationSender())->send($user);
    }

    /**
     * @throws \Exception
     */
    private function convertUserIdentifierTypeToNotificationType(UserIdentifierType $identifier): NotificationTypes
    {
        return match ($identifier) {
            UserIdentifierType::EMAIL => NotificationTypes::EMAIL,
            UserIdentifierType::PHONE => NotificationTypes::PHONE,
            default => throw new \Exception()
        };
    }
}
