<?php

namespace App\Models\Person\Editable\Request;

final class ParentModel
{
    public function __construct(
        private int $person,
        private int $role
    ) {
    }

    public function getPerson(): int
    {
        return $this->person;
    }

    public function getRole(): int
    {
        return $this->role;
    }
}
