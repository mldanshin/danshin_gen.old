<?php

namespace App\Services\Events;

final class TelegramUser
{
    public function __construct(private string $id, private ?string $username)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
}
