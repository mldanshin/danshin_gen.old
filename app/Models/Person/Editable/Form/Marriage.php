<?php

namespace App\Models\Person\Editable\Form;

use App\Models\Pair;
use App\Models\PersonShort;
use Illuminate\Support\Collection;

final class Marriage
{
    /**
     * @param Collection|Pair[] $roleCurrentOptions
     * @param Collection|PersonShort[] $soulmateOptions
     * @param Collection|Pair[] $roleSoulmateOptions
     */
    public function __construct(
        private int $roleCurrent,
        private Collection $roleCurrentOptions,
        private int $soulmate,
        private Collection $soulmateOptions,
        private int $roleSoulmate,
        private Collection $roleSoulmateOptions,
    ) {
    }

    public function getRoleCurrent(): int
    {
        return $this->roleCurrent;
    }

    /**
     * @return Collection|Pair[]
     */
    public function getRoleCurrentOptions(): Collection
    {
        return $this->roleCurrentOptions;
    }

    public function getSoulmate(): int
    {
        return $this->soulmate;
    }

    /**
     * @return Collection|PersonShort[]
     */
    public function getSoulmateOptions(): Collection
    {
        return $this->soulmateOptions;
    }

    public function getRoleSoulmate(): int
    {
        return $this->roleSoulmate;
    }

    /**
     * @return Collection|Pair[]
     */
    public function getRoleSoulmateOptions(): Collection
    {
        return $this->roleSoulmateOptions;
    }
}
