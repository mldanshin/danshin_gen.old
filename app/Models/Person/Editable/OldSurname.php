<?php

namespace App\Models\Person\Editable;

final class OldSurname
{
    public function __construct(
        private string $surname,
        private int $order,
    ) {
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getOrder(): int
    {
        return $this->order;
    }
}
