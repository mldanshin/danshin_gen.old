<?php

namespace App\Models\Person\Editable\Request;

final class Marriage
{
    public function __construct(
        private int $roleCurrent,
        private int $soulmate,
        private int $roleSoulmate
    ) {
    }

    public function getRoleCurrent(): int
    {
        return $this->roleCurrent;
    }

    public function getSoulmate(): int
    {
        return $this->soulmate;
    }

    public function getRoleSoulmate(): int
    {
        return $this->roleSoulmate;
    }
}
