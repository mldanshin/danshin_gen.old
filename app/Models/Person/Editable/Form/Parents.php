<?php

namespace App\Models\Person\Editable\Form;

use App\Models\Pair;
use Illuminate\Support\Collection;

final class Parents
{
    /**
     * @param Collection|Pair[] $roleOptions
     * @param Collection|ParentModel[] $parent
     */
    public function __construct(
        private Collection $roleOptions,
        private Collection $parent,
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
     * @return Collection|ParentModel[]
     */
    public function getParent(): Collection
    {
        return $this->parent;
    }
}
