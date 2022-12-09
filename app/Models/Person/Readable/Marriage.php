<?php

namespace App\Models\Person\Readable;

use App\Models\PersonShort;

final class Marriage
{
    public function __construct(
        private PersonShort $soulmate,
        private int $role
    ) {
    }

    public function getSoulmate(): PersonShort
    {
        return $this->soulmate;
    }

    public function getRole(): int
    {
        return $this->role;
    }
}
