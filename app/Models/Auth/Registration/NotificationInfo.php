<?php

namespace App\Models\Auth\Registration;

use App\Services\NotificationTypes;

final class NotificationInfo
{
    public function __construct(
        private int $idUser,
        private NotificationTypes $type,
        private string $address
    ) {
    }

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function getType(): NotificationTypes
    {
        return $this->type;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
