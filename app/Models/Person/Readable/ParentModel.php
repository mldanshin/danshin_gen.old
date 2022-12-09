<?php

namespace App\Models\Person\Readable;

use App\Models\PersonShort;

final class ParentModel
{
    public function __construct(
        private PersonShort $person,
        private int $role,
    ) {
    }

    public function getPerson(): PersonShort
    {
        return $this->person;
    }

    public function getRole(): int
    {
        return $this->role;
    }
}
