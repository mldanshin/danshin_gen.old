<?php

namespace App\Models\Auth\Registration;

final class ConfirmationCodeForm
{
    public function __construct(
        private int $idUserUnconfirmed,
        private int $attempts,
        private int $timestamp,
        private int $repeatTimestamp
    ) {
    }

    public function getId(): int
    {
        return $this->idUserUnconfirmed;
    }

    public function getAttempts(): int
    {
        return $this->attempts;
    }

    public function getTimeStamp(): int
    {
        return $this->timestamp;
    }

    public function getRepeatTimestamp(): int
    {
        return $this->repeatTimestamp;
    }
}
