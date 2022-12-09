<?php

namespace App\Models\Person\Editable\Form;

use App\Models\Pair;
use App\Models\PersonShort;
use Illuminate\Support\Collection;

final class ParentModel
{
     /**
     * @param Collection|PersonShort[] $personOptions
     * @param Collection|Pair[] $roleOptions
     */
    public function __construct(
        private int $person,
        private Collection $personOptions,
        private int $role,
        private Collection $roleOptions,
    ) {
    }

    public function getPerson(): int
    {
        return $this->person;
    }

    /**
     * @return Collection|PersonShort[]
     */
    public function getPersonOptions(): Collection
    {
        return $this->personOptions;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @return Collection|Pair[]
     */
    public function getRoleOptions(): Collection
    {
        return $this->roleOptions;
    }
}
