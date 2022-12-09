<?php

namespace App\Models\Person\Editable\Form;

use App\Models\Pair;
use Illuminate\Support\Collection;

final class Marriages
{
    /**
         * @param Collection|Pair[] $roleOptions
         * @param Collection|Marriage[] $marriage
         */
    public function __construct(
        private Collection $roleOptions,
        private Collection $marriage,
    ) {
    }

    /**
     * @return Collection|Pair[]
     */
    public function getRoleOptions(): Collection
    {
        return $this->roleOptions;
    }

    /**
     * @return Collection|Marriage[]
     */
    public function getMarriage(): Collection
    {
        return $this->marriage;
    }
}
