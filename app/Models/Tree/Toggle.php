<?php

namespace App\Models\Tree;

use Illuminate\Support\Collection;

final class Toggle
{
    /**
     * @param Collection|PersonShort[] $list
     */
    public function __construct(
        private Collection $list,
        private int $active
    ) {
    }

    /**
     * @return Collection|PersonShort[]
     */
    public function getList(): Collection
    {
        return $this->list;
    }

    public function getActive(): int
    {
        return $this->active;
    }
}
